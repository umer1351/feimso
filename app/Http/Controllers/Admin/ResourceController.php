<?php

namespace App\Http\Controllers\Admin;

use App\Admin\DocRepository;
use App\Admin\Fields;
use App\Admin\Hooks;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/** Generic list / create / edit / delete for every resource in config/admin.php. */
class ResourceController extends Controller
{
    public function __construct(private DocRepository $repo, private Hooks $hooks)
    {
    }

    private function resource(string $name): array
    {
        $r = config("admin.resources.$name");
        abort_unless($r, 404);
        return $r + ['create' => true, 'edit' => true, 'delete' => true, 'search' => [], 'filters' => [], 'orders' => false];
    }

    public function index(Request $request, string $name)
    {
        $r = $this->resource($name);
        $filters = [];
        foreach ($r['filters'] as $field => $def) {
            $filters[$field] = $request->query("f_$field");
        }
        $docs = $this->repo->paginate($name, $request->query('q'), $r['search'], $filters, $request->query('sort'));

        // Resolve "ref:collection.label" columns in one pass per collection.
        $refs = [];
        foreach ($r['columns'] as $key => $col) {
            if (is_string($col) && str_starts_with($col, 'ref:')) {
                [$coll, $label] = explode('.', substr($col, 4));
                $refs[$coll . '.' . $label] = $this->repo->options($coll, $label);
            }
        }

        $filterOptions = [];
        foreach ($r['filters'] as $field => $def) {
            $filterOptions[$field] = $def['type'] === 'bool' ? ['1' => __('admin.yes'), '0' => __('admin.no')] : Fields::options($def, $this->repo);
        }

        return view('admin.resource.index', compact('name', 'r', 'docs', 'refs', 'filters', 'filterOptions'));
    }

    public function create(string $name)
    {
        $r = $this->resource($name);
        abort_unless($r['create'], 403);
        return $this->form($name, $r, null, null);
    }

    public function edit(string $name, string $id)
    {
        $r = $this->resource($name);
        $doc = $this->repo->find($name, $id);
        abort_unless($doc !== null, 404);
        return $this->form($name, $r, $id, $doc);
    }

    private function form(string $name, array $r, ?string $id, ?array $doc)
    {
        $options = [];
        foreach ($r['fields'] as $field => $def) {
            if (in_array($def['type'], ['select', 'multiselect'], true)) {
                $options[$field] = Fields::options($def, $this->repo);
            }
        }
        return view('admin.resource.form', compact('name', 'r', 'id', 'doc', 'options'));
    }

    public function store(Request $request, string $name)
    {
        $r = $this->resource($name);
        abort_unless($r['create'], 403);
        $id = DocRepository::newId();
        if ($name === 'users') {
            $id = \App\Models\User::newUid();
        }
        $this->persist($request, $name, $r, $id, null);
        return redirect()->route('admin.resource.index', $name)->with('ok', __('admin.saved'));
    }

    public function update(Request $request, string $name, string $id)
    {
        $r = $this->resource($name);
        abort_unless($r['edit'], 403);
        $existing = $this->repo->find($name, $id);
        abort_unless($existing !== null, 404);
        $this->persist($request, $name, $r, $id, $existing);
        return redirect()->route('admin.resource.edit', [$name, $id])->with('ok', __('admin.saved'));
    }

    private function persist(Request $request, string $name, array $r, string $id, ?array $existing): void
    {
        $changes = [];
        foreach ($r['fields'] as $field => $def) {
            $changes += Fields::fromRequest($field, $def, $request, $name);
        }
        if ($name === 'users' && $existing && $id === (string) Auth::id() && ($changes['role'] ?? 'admin') !== 'admin') {
            unset($changes['role']); // don't let an admin demote themselves by accident
        }
        $changes = $this->hooks->beforeSave($name, $id, $changes, $existing);
        $this->repo->save($name, $id, $changes);
    }

    public function show(string $name, string $id)
    {
        $r = $this->resource($name);
        $doc = $this->repo->find($name, $id);
        abort_unless($doc !== null, 404);
        unset($doc['password'], $doc['remember_token']);
        return view('admin.resource.show', compact('name', 'r', 'id', 'doc'));
    }

    public function destroy(string $name, string $id)
    {
        $r = $this->resource($name);
        abort_unless($r['delete'], 403);
        abort_if($name === 'users' && $id === (string) Auth::id(), 422, __('admin.cannot_delete_self'));
        $this->repo->delete($name, $id);
        return redirect()->route('admin.resource.index', $name)->with('ok', __('admin.deleted'));
    }

    /** Quick status change from the order list/detail. */
    public function status(Request $request, string $name, string $id)
    {
        $r = $this->resource($name);
        abort_unless($r['orders'], 404);
        $status = (string) $request->input('status');
        $allowed = $r['fields']['status']['options'] ?? config('admin.order_statuses');
        abort_unless(in_array($status, $allowed, true), 422);
        $this->repo->save($name, $id, ['status' => $status]);
        return back()->with('ok', __('admin.status_updated'));
    }
}
