<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->cascadeOnDelete();
            $table->unsignedInteger('rank_from');
            $table->unsignedInteger('rank_to');
            $table->string('title');
            $table->decimal('prize_amount', 10, 2);
            $table->string('prize_unit')->default('gram_gold');
            $table->unsignedSmallInteger('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->index(['campaign_id', 'rank_from', 'rank_to']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prizes');
    }
};
