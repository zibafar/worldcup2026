<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'match_id',
        'predicted_home_score',
        'predicted_away_score',
        'points',
        'result_type',
        'locked_at',
        'calculated_at',
    ];

    protected $casts = [
        'predicted_home_score' => 'integer',
        'predicted_away_score' => 'integer',
        'points' => 'integer',
        'locked_at' => 'datetime',
        'calculated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(FootballMatch::class, 'match_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(PredictionLog::class);
    }
}
