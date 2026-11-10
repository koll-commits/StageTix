<?php

namespace App\Modules\Catalog\Services;

use App\Models\Event;
use App\Models\Venue;
use Illuminate\Support\Collection;

/**
 * Query lintas-tenant yang SAH untuk katalog publik satu venue. Bypass global scope
 * dilakukan eksplisit lalu diganti filter pengganti: hanya tenant berstatus aktif,
 * event berstatus published, dan milik venue tersebut. Ditempatkan di class khusus agar mudah diaudit.
 */
final class PublicCatalogQuery
{
    /** @return Collection<int, Event> */
    public function forVenue(Venue $venue): Collection
    {
        return Event::query()
            ->withoutGlobalScope('tenant') // bypass eksplisit, bukan diam-diam
            ->where('status', 'published')
            ->where('venue_id', $venue->id)
            ->whereHas('tenant', function ($query): void {
                $query->where('status', 'active');
            })
            ->orderBy('title')
            ->get();
    }

    /** Kompatibilitas jika dipanggil dengan forCanteen atau objek venue */
    public function forCanteen(mixed $canteen): Collection
    {
        $venueId = is_object($canteen) ? $canteen->id : $canteen;

        return Event::query()
            ->withoutGlobalScope('tenant') // bypass eksplisit, bukan diam-diam
            ->where('status', 'published')
            ->where('venue_id', $venueId)
            ->whereHas('tenant', function ($query): void {
                $query->where('status', 'active');
            })
            ->orderBy('title')
            ->get();
    }
}

