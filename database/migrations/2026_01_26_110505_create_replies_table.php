<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('replies', function (Blueprint $table) {
            $table->id();
            $table->text('messages');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('parent_id')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('replies')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('replies', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['parent_id']);
        });

        Schema::dropIfExists('replies');
    }
};
