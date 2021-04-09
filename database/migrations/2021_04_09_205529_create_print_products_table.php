<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('print_products', function (Blueprint $table) {
            $table->id();
            $table->string('shop');
            $table->string('title')->nullable();
            $table->longText('body_html')->nullable();
            $table->string('vendor')->nullable();
            $table->string('product_type')->nullable();
            $table->longText('tags')->nullable();
            $table->longText('options')->nullable();
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
        Schema::dropIfExists('print_products');
    }
}
