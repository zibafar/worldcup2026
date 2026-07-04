<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('mobile')->unique();
            $table->string('email')->nullable()->unique();
            $table->string('password')->nullable();
            $table->string('avatar')->nullable();
            $table->unsignedInteger('total_score')->default(0)->index();
            $table->unsignedInteger('exact_predictions_count')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
