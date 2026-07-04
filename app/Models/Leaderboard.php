<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leaderboard extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'user_id',
        'total_score',
        'exact_predictions_count',
        'rank',
        'calculated_at',
    ];

    protected $casts = [
        'total_score' => 'decimal:2',
        'exact_predictions_count' => 'integer',
        'rank' => 'integer',
        'calculated_at' => 'datetime',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
