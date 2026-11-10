<?php

namespace App\Models;

use Database\Factories\GateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gate extends Model
{
    /** @use HasFactory<GateFactory> */
    use HasFactory;

    protected $fillable = ['venue_id', 'code', 'name', 'status'];

    /** @return BelongsTo<Venue, $this> */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }
}
