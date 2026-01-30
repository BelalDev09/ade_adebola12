<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_contents', function (Blueprint $table) {
            $table->id();
            $table->string('page_slug');
            $table->string('section');
            $table->string('type')->nullable();
            $table->text('content')->nullable();
            $table->string('image_path')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('btn_text')->nullable();
            $table->string('btn_link')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_contents');
    }
};
