<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('match_id')->constrained('matches')->cascadeOnDelete();
            $table->unsignedTinyInteger('predicted_home_score');
            $table->unsignedTinyInteger('predicted_away_score');
            $table->unsignedInteger('points')->default(0)->index();
            $table->enum('result_type', ['pending', 'exact', 'winner_goal_diff', 'winner_only', 'wrong'])->default('pending')->index();
            $table->timestamp('locked_at')->nullable();
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'match_id']);
            $table->index(['match_id', 'result_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('predictions');
    }
};
