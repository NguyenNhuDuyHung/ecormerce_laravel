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
        Schema::create('product_catalogue_product', function (Blueprint $table) {
    $table->unsignedBigInteger('product_catalogue_id');
    $table->unsignedBigInteger('product_id');
    $table->foreign('product_catalogue_id')->references('id')->on('product_catalogues')->onDelete('cascade');
    $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('product_catalogue_product');
    }
};