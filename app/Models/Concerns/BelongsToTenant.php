<?php

namespace App\Models\Concerns;

use App\Support\Tenancy\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Model tenant-owned: menambahkan global scope eo_id dan mengisi eo_id saat create
 * dari TenantContext aktif. Auto-fill membantu konsistensi, tetapi policy + composite FK
 * database tetap wajib (pertahanan berlapis). eo_id TIDAK boleh mass-assignable.
 *
 * Catatan: scope hanya memfilter saat context terisi. Route internal SELALU mengisi context
 * (SetTenantContext); alur lintas-tenant yang sah memakai withoutGlobalScope('tenant') + filter
 * eksplisit. Write tanpa context gagal via NOT NULL eo_id di DB (fail-closed).
 *
 * @method static void addGlobalScope(string $name, \Closure $implementation)
 * @method static void creating(\Closure $callback)
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope('tenant', function (Builder $builder): void {
            $context = app(TenantContext::class);
            if ($context->has()) {
                $builder->where($builder->qualifyColumn('eo_id'), $context->id());
            }
        });

        static::creating(function (Model $model): void {
            $context = app(TenantContext::class);
            if ($context->has() && empty($model->getAttribute('eo_id'))) {
                $model->setAttribute('eo_id', $context->id());
            }
        });
    }
}

