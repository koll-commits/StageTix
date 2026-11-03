<?php

namespace App\Http\Controllers\Eo;

use App\Http\Controllers\Controller;
use App\Models\TicketArea;
use App\Models\TicketCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TicketCategoryController extends Controller
{
    public function store(Request $request, int $event): RedirectResponse
    {
        $tenant = $request->attributes->get('tenant');
        $event = $tenant->events()->findOrFail($event);

        $data = $request->validateWithBag('category', [
            'name' => ['required', 'string', 'max:120',
                Rule::unique('ticket_categories', 'name')->where('tenant_id', $tenant->id)->where('event_id', $event->id)],
            'area_id' => ['required', Rule::exists('ticket_areas', 'id')->where('tenant_id', $tenant->id)->where('event_id', $event->id)],
            'price_amount' => ['required', 'integer', 'min:0', 'max:100000000'],
            'quota' => ['required', 'integer', 'min:1'],
        ]);

        $area = TicketArea::findOrFail($data['area_id']);
        $this->assertWithinCapacity($area, (int) $data['quota'], null);

        $category = new TicketCategory($data);
        $category->tenant_id = $tenant->id;
        $category->event_id = $event->id;
        $category->sort_order = $event->ticketCategories()->count() + 1;
        $category->save();

        return back()->with('status', 'Kategori "'.$category->name.'" ditambahkan.');
    }

    public function update(Request $request, int $category): RedirectResponse
    {
        $tenant = $request->attributes->get('tenant');
        $category = TicketCategory::where('tenant_id', $tenant->id)->findOrFail($category);

        $data = $request->validateWithBag('category', [
            'name' => ['required', 'string', 'max:120',
                Rule::unique('ticket_categories', 'name')->where('tenant_id', $tenant->id)
                    ->where('event_id', $category->event_id)->ignore($category->id)],
            'price_amount' => ['required', 'integer', 'min:0', 'max:100000000'],
            'quota' => ['required', 'integer', 'min:'.max(1, $category->sold_qty)],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $this->assertWithinCapacity($category->area, (int) $data['quota'], $category->id);

        $category->update([
            'name' => $data['name'],
            'price_amount' => $data['price_amount'],
            'quota' => $data['quota'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('status', 'Kategori "'.$category->name.'" diperbarui.');
    }

    /** Sakelar sold out satu langkah (FR-EO-03). */
    public function toggleSoldOut(Request $request, int $category): RedirectResponse
    {
        $category = TicketCategory::where('tenant_id', $request->attributes->get('tenant')->id)->findOrFail($category);

        if ($category->is_sold_out && $category->sold_qty >= $category->quota) {
            return back()->with('error', 'Kuota "'.$category->name.'" memang sudah habis terjual.');
        }

        $category->update(['is_sold_out' => ! $category->is_sold_out]);

        return back()->with('status', '"'.$category->name.'" ditandai '.($category->is_sold_out ? 'sold out.' : 'tersedia lagi.'));
    }

    /** Total kuota kategori dalam satu area tidak boleh melebihi kapasitas area. */
    private function assertWithinCapacity(TicketArea $area, int $quota, ?int $ignoreId): void
    {
        $used = (int) $area->ticketCategories()
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->sum('quota');

        if ($used + $quota > $area->capacity) {
            throw ValidationException::withMessages([
                'quota' => 'Total kuota area "'.$area->name.'" melebihi kapasitas ('.$area->capacity.'). Sisa: '.max(0, $area->capacity - $used).'.',
            ])->errorBag('category');
        }
    }
}
