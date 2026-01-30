<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Reviews table
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('reviews')->cascadeOnDelete();
            $table->foreignId('location_id')->nullable()->constrained('locations')->cascadeOnDelete();
             $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->tinyInteger('rating')->nullable();
            $table->string('role')->nullable();
            $table->text('description')->nullable();
            $table->text('messages')->nullable();
            $table->json('medias')->nullable();
            $table->unsignedInteger('likes')->default(0);
             $table->boolean('is_active')->default(true);   
            $table->timestamps();
        });

        // Pivot table for locations & reviews
        Schema::create('location_review', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        // Likes table
        Schema::create('review_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('liked')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_likes');
        Schema::dropIfExists('location_review');
        Schema::dropIfExists('reviews');
    }
};
