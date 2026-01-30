<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('location_review')) {
            Schema::create('location_review', function (Blueprint $table) {
                $table->id();
                $table->foreignId('review_id')->constrained()->cascadeOnDelete();
                $table->foreignId('location_id')->constrained()->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['review_id', 'location_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('location_review');
    }
};
