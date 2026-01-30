<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('system_title')->nullable();
            $table->string('copyright_text')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
