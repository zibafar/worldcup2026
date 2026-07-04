<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('match_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('matches')->cascadeOnDelete();
            $table->foreignId('team_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->foreignId('player_team_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->enum('event_type', ['goal', 'yellow_card', 'red_card', 'penalty', 'own_goal', 'var', 'substitution', 'other'])->index();
            $table->unsignedSmallInteger('minute')->nullable();
            $table->string('description')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['match_id', 'event_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_events');
    }
};
