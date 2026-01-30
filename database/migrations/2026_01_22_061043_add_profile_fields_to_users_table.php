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
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('phone')->nullable()->after('email');
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('designation')->nullable();
            $table->string('website')->nullable();
            $table->text('description')->nullable(); // About / Bio
            $table->string('avatar')->nullable();     // Profile photo
            $table->string('cover_image')->nullable(); // Cover photo
            $table->json('skills')->nullable();       // ["Photoshop", "HTML", "CSS"]
            $table->json('portfolio')->nullable();    // [{"name": "GitHub", "url": "..."}, ...]
            $table->date('joining_date')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
