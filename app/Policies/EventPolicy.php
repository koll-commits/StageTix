<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use App\Support\Tenancy\TenantContext;

/**
 * Policy = lapisan kemampuan. Binding membatasi objek; policy membatasi aksi.
 * Defense-in-depth: event harus milik tenant aktif (selain global scope + composite FK).
 */
class EventPolicy
{
    private function ownedByActiveTenant(Event $event): bool
    {
        return $event->eo_id === app(TenantContext::class)->idOrNull();
    }

    public function view(User $user, Event $event): bool
    {
        return $this->ownedByActiveTenant($event);
    }

    public function update(User $user, Event $event): bool
    {
        return $this->ownedByActiveTenant($event);
    }

    public function delete(User $user, Event $event): bool
    {
        return $this->ownedByActiveTenant($event);
    }
}

