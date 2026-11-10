<?php

namespace App\Policies;

use App\Models\EventOrder;
use App\Models\User;
use App\Support\Tenancy\TenantContext;

class EventOrderPolicy
{
    private function ownedByActiveTenant(EventOrder $order): bool
    {
        return $order->eo_id === app(TenantContext::class)->idOrNull();
    }

    public function view(User $user, EventOrder $order): bool
    {
        return $this->ownedByActiveTenant($order);
    }

    public function update(User $user, EventOrder $order): bool
    {
        return $this->ownedByActiveTenant($order);
    }
}

