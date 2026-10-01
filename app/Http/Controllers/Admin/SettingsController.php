<?php

namespace App\Http\Controllers\Admin;

use App\Admin\DocRepository;
use App\Admin\Fields;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/** Tabbed editor for the `settings` documents (config/admin_settings.php). */
class SettingsController extends Controller
{
    public function __construct(private DocRepository $repo)
    {
    }

    public function edit(string $tab = 'general')
    {
        $tabs = config('admin_settings');
        abort_unless(isset($tabs[$tab]), 404);
        $docs = [];
        $options = [];
        foreach ($tabs[$tab]['docs'] as $docId => $fields) {
            $docs[$docId] = $this->repo->find('settings', $docId) ?? [];
            foreach ($fields as $f => $def) {
                if ($def['type'] === 'select') {
                    $options[$docId][$f] = Fields::options($def, $this->repo);
                }
            }
        }
        return view('admin.settings', compact('tabs', 'tab', 'docs', 'options'));
    }

    public function update(Request $request, string $tab)
    {
        $tabs = config('admin_settings');
        abort_unless(isset($tabs[$tab]), 404);

        // Inputs are namespaced "<docId>__<field>" so several docs share one form.
        foreach ($tabs[$tab]['docs'] as $docId => $fields) {
            $changes = [];
            $sub = Request::create('/', 'POST', $this->slice($request->all(), $docId));
            $sub->files->replace($this->slice($request->allFiles(), $docId));
            foreach ($fields as $f => $def) {
                $changes += Fields::fromRequest($f, $def, $sub, 'settings');
            }
            $this->repo->save('settings', $docId, $changes);
        }
        return redirect()->route('admin.settings', $tab)->with('ok', __('admin.saved'));
    }

    private function slice(array $input, string $docId): array
    {
        $out = [];
        $prefix = $docId . '__';
        foreach ($input as $k => $v) {
            if (str_starts_with($k, $prefix)) {
                $out[substr($k, strlen($prefix))] = $v;
            }
        }
        return $out;
    }
}
