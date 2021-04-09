<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('shop');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('print_product_id')->nullable();
            $table->string('title')->nullable();
            $table->string('vendor')->nullable();
            $table->string('product_type')->nullable();
            $table->longText('tags')->nullable();
            $table->longText('options')->nullable();
            $table->longText('body_html')->nullable();
            $table->longText('images')->nullable();
            $table->longText('image')->nullable();
            $table->longText('variants')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
