<?php

namespace App\Firestore;

use Illuminate\Support\Facades\Auth;

/**
 * Per-collection access rules — the MySQL replacement for Firestore security rules.
 *
 * read:  public | auth | owner | admin
 * write: none   | auth | owner | admin
 * owner: the document field holding the owning user's id ('id' for users).
 * shared: fields any signed-in user may update() on an existing doc.
 * protected: fields the browser may read but never write.
 *
 * Admins (users.role = 'admin') bypass every rule.
 */
class Access
{
    public function uid(): ?string
    {
        return Auth::id() !== null ? (string) Auth::id() : null;
    }

    public function isAdmin(): bool
    {
        return Auth::check() && (Auth::user()->role ?? null) === 'admin';
    }

    public function assertCanRead(CollectionSchema $c): void
    {
        if ($this->isAdmin() || $c->read === 'public') {
            return;
        }
        if (in_array($c->read, ['auth', 'owner'], true) && $this->uid() !== null) {
            return;
        }
        throw new AccessDenied('Missing or insufficient permissions.');
    }

    /** Extra filters forced onto every query (owner-only collections see only their rows). */
    public function readConstraints(CollectionSchema $c): array
    {
        if ($this->isAdmin() || $c->read !== 'owner' || !$c->owner) {
            return [];
        }
        return [[$c->owner === 'id' ? '__name__' : $c->owner, '==', $this->uid()]];
    }

    public function canReadDoc(CollectionSchema $c, array $doc): bool
    {
        if ($this->isAdmin() || $c->read !== 'owner' || !$c->owner) {
            return true;
        }
        return $this->ownerOf($c, $doc['id'], $doc['data']) === $this->uid();
    }

    /**
     * @param string[]|null $updatedFields top-level fields of an update(); null for set/delete
     */
    public function assertCanWrite(CollectionSchema $c, string $id, ?array $existing, ?array $new, ?array $updatedFields): void
    {
        if ($this->isAdmin()) {
            return;
        }
        $uid = $this->uid();

        // "Shared" fields (rating totals, stock) may be updated on someone else's doc.
        if ($uid !== null && $existing !== null && $updatedFields !== null && $c->shared
            && !array_diff($updatedFields, $c->shared)) {
            return;
        }

        $ok = match ($c->write) {
            'auth' => $uid !== null,
            'owner' => $uid !== null
                && ($existing === null || $this->ownerOf($c, $id, $existing) === $uid)
                && ($new === null || $this->ownerOf($c, $id, $new) === $uid),
            default => false,
        };
        if (!$ok) {
            throw new AccessDenied("Missing or insufficient permissions to write {$c->name}/$id.");
        }
    }

    private function ownerOf(CollectionSchema $c, string $id, array $data): ?string
    {
        if ($c->owner === 'id') {
            return $id;
        }
        $v = $data[$c->owner] ?? null;
        return is_scalar($v) ? (string) $v : null;
    }
}
