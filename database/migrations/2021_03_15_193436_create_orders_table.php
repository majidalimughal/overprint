<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('shop')->nullable();
            $table->unsignedBigInteger('shopify_order_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->float('discount_amount')->nullable();
            $table->string('fulfillment_status')->nullable();
            $table->string('status')->default('open');
            $table->string('financial_status')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->float('price')->nullable();
            $table->float('total_weight')->nullable();
            $table->float('total_tax')->nullable();
            $table->float('total_line_items_price')->nullable();
            $table->text('shipping_address')->nullable();
            $table->text('billing_address')->nullable();
            $table->longText('shipping_lines')->nullable();
            $table->longText('tracking_number')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
