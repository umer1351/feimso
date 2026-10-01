<?php

namespace App\Http\Controllers;

use App\Firestore\AccessDenied;
use App\Firestore\Engine;
use App\Firestore\NotFound;
use Illuminate\Http\Request;
use InvalidArgumentException;

/**
 * Backend for public/js/firebase-local.js.
 *
 * POST /fs/batch {requests: [{t:'get', c, id} | {t:'query', c, q} | {t:'write', ops}]}
 *   -> {results: [{ok:true, ...} | {ok:false, code, message}]}
 *
 * The browser coalesces every Firestore call made in the same tick into one
 * batch, so a page that fires 200 small reads costs one HTTP round trip.
 * Requests run in order and fail independently.
 */
class FirestoreController extends Controller
{
    private const MAX_REQUESTS = 500;

    public function batch(Request $request, Engine $engine)
    {
        $requests = $request->input('requests', []);
        if (!is_array($requests) || count($requests) > self::MAX_REQUESTS) {
            return response()->json(['message' => 'Invalid batch'], 422);
        }

        $results = [];
        foreach ($requests as $r) {
            try {
                $results[] = ['ok' => true] + $this->run($engine, (array) $r);
            } catch (AccessDenied $e) {
                $results[] = ['ok' => false, 'code' => 'permission-denied', 'message' => $e->getMessage()];
            } catch (NotFound $e) {
                $results[] = ['ok' => false, 'code' => 'not-found', 'message' => $e->getMessage()];
            } catch (InvalidArgumentException $e) {
                $results[] = ['ok' => false, 'code' => 'invalid-argument', 'message' => $e->getMessage()];
            } catch (\Throwable $e) {
                report($e);
                $results[] = ['ok' => false, 'code' => 'internal', 'message' => config('app.debug') ? $e->getMessage() : 'Internal error'];
            }
        }
        return response()->json(['results' => $results]);
    }

    private function run(Engine $engine, array $r): array
    {
        switch ($r['t'] ?? null) {
            case 'get':
                $doc = $engine->getDoc((string) ($r['c'] ?? ''), (string) ($r['id'] ?? ''));
                return ['doc' => $doc];
            case 'query':
                return ['docs' => $engine->query((string) ($r['c'] ?? ''), (array) ($r['q'] ?? []))];
            case 'write':
                $engine->write((array) ($r['ops'] ?? []));
                return [];
            default:
                throw new InvalidArgumentException('Unknown request type');
        }
    }
}
