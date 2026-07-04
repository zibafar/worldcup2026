<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name_fa');
            $table->string('name_en');
            $table->string('fifa_code', 10)->nullable()->unique();
            $table->string('flag_url')->nullable();
            $table->timestamps();

            $table->index(['name_fa', 'name_en']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
