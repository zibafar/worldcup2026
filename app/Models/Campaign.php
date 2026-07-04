<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'start_at',
        'end_at',
        'total_prize_title',
        'is_active',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function matches(): HasMany
    {
        return $this->hasMany(FootballMatch::class, 'campaign_id');
    }

    public function prizes(): HasMany
    {
        return $this->hasMany(Prize::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class);
    }

    public function scoringRules(): HasMany
    {
        return $this->hasMany(ScoringRule::class);
    }
}
