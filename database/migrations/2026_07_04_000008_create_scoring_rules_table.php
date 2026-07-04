<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scoring_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->nullable()->constrained('campaigns')->cascadeOnDelete();
            $table->string('key')->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('points');
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedSmallInteger('sort_order')->default(0)->index();
            $table->timestamps();

            $table->unique(['campaign_id', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scoring_rules');
    }
};
