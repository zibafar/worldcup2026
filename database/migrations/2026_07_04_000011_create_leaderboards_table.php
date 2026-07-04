<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaderboards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('total_score')->default(0)->index();
            $table->unsignedInteger('exact_predictions_count')->default(0)->index();
            $table->unsignedInteger('rank')->nullable()->index();
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();

            $table->unique(['campaign_id', 'user_id']);
            $table->index(['campaign_id', 'rank']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaderboards');
    }
};
