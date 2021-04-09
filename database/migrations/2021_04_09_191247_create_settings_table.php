<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('shop');
            $table->string('bill_first_name')->nullable();
            $table->string('bill_last_name')->nullable();
            $table->string('bill_email')->nullable();
            $table->text('bill_address')->nullable();
            $table->text('bill_city')->nullable();
            $table->text('bill_country')->nullable();
            $table->text('bill_region')->nullable();
            $table->string('billing_method_default')->nullable();
            $table->text('card_number')->nullable();
            $table->text('card_exp_month')->nullable();
            $table->text('card_exp_year')->nullable();
            $table->text('card_cvc')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
