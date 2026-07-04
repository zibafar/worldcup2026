<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prediction_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prediction_id')->constrained('predictions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('match_id')->constrained('matches')->cascadeOnDelete();
            $table->unsignedTinyInteger('old_home_score')->nullable();
            $table->unsignedTinyInteger('old_away_score')->nullable();
            $table->unsignedTinyInteger('new_home_score');
            $table->unsignedTinyInteger('new_away_score');
            $table->timestamps();

            $table->index(['user_id', 'match_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prediction_logs');
    }
};
