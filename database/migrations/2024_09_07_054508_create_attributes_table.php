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
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->integer('attribute_catalogue_id')->default(0);
            $table->string('image')->nullable();
            $table->string('icon')->nullable();
            $table->text('album')->nullable();
            $table->tinyInteger('publish')->default(1);
            $table->tinyInteger('follow')->default(1);
            $table->integer('order')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Sử dụng onDelete('cascade')
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('attributes');
    }
};