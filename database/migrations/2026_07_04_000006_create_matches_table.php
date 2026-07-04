<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->cascadeOnDelete();
            $table->foreignId('stage_id')->constrained('stages')->restrictOnDelete();
            $table->foreignId('home_team_id')->constrained('teams')->restrictOnDelete();
            $table->foreignId('away_team_id')->constrained('teams')->restrictOnDelete();
            $table->timestamp('match_date')->index();
            $table->timestamp('prediction_deadline')->index();
            $table->unsignedTinyInteger('home_score')->nullable();
            $table->unsignedTinyInteger('away_score')->nullable();
            $table->enum('status', ['scheduled', 'live', 'finished', 'cancelled'])->default('scheduled')->index();
            $table->timestamps();

            $table->index(['campaign_id', 'status', 'match_date']);
            $table->unique(['campaign_id', 'home_team_id', 'away_team_id', 'match_date'], 'matches_unique_game');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
