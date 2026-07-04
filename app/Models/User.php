<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'mobile',
        'email',
        'password',
        'avatar',
        'total_score',
        'exact_predictions_count',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'total_score' => 'decimal:2',
        'exact_predictions_count' => 'integer',
        'is_active' => 'boolean',
    ];

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class);
    }

    public function leaderboardRows(): HasMany
    {
        return $this->hasMany(Leaderboard::class);
    }
}
