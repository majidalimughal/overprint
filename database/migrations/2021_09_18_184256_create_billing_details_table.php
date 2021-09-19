<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_details', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('email');
            $table->text('phone')->nullable();
            $table->text('address')->nullable();
            $table->text('city')->nullable();
            $table->string('zip');
            $table->string('country');
            $table->longText('stripe_customer_id')->nullable();
            $table->longText('stripe_payment_method_id')->nullable();
            $table->unsignedBigInteger('shop_id');
            $table->string('active_method')->default('stripe');
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
        Schema::dropIfExists('billing_details');
    }
}
