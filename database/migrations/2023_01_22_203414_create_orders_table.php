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
            $table->timestamps();
            $table->string('currency_purchased');
            $table->string('currency_purchased_ISO');
            $table->decimal('exchange_rate',14,6);
            $table->tinyInteger('surcharge');
            $table->decimal('surcharge_amount',19,6);
            $table->decimal('amount_purchased',19,6);
            $table->decimal('amount_paid',19,6);
            $table->string('currency_paid');
            $table->string('currency_paid_ISO');
            $table->tinyInteger('discount');
            $table->decimal('discount_amount',19,6);
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
