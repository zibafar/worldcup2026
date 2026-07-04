<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PredictionLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'prediction_id',
        'user_id',
        'match_id',
        'old_home_score',
        'old_away_score',
        'new_home_score',
        'new_away_score',
        'created_at',
    ];

    protected $casts = [
        'old_home_score' => 'integer',
        'old_away_score' => 'integer',
        'new_home_score' => 'integer',
        'new_away_score' => 'integer',
        'created_at' => 'datetime',
    ];

    public function prediction(): BelongsTo
    {
        return $this->belongsTo(Prediction::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(FootballMatch::class, 'match_id');
    }
}
