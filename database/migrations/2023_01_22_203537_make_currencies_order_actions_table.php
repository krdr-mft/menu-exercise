<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeCurrenciesOrderActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies_order_actions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('currency',3);
            $table->foreign('currency')
                ->references('ISO')
                ->on('currencies')->onDelete('cascade');

            $table->unsignedBigInteger('action');
            $table->foreign('action')
                ->references('id')
                ->on('order_actions')->onDelete('cascade');

            $table->string('parameter');
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies_order_actions');
    }
}
