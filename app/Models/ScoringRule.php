<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScoringRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'key',
        'title',
        'description',
        'points',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'points' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}
