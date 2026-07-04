<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prize extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'rank_from',
        'rank_to',
        'title',
        'prize_amount',
        'prize_unit',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'rank_from' => 'integer',
        'rank_to' => 'integer',
        'prize_amount' => 'decimal:2',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}
