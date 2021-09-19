<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentCyclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_cycles', function (Blueprint $table) {
            $table->id();
            $table->date('from');
            $table->date('to');
            $table->float('charge');
            $table->boolean('paid')->default(false);
            $table->timestamp('paid_at')->nullable();
            $table->boolean('overdue')->default(false);
            $table->unsignedBigInteger('shop_id');
            $table->string('method')->default('stripe');
            $table->unsignedBigInteger('receipt_url')->nullable();
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
        Schema::dropIfExists('payment_cycles');
    }
}
