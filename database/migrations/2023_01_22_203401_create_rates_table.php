<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('from',3);
            $table->foreign('from')
                ->references('ISO')
                ->on('currencies')->onDelete('cascade');
            $table->string('to', 3);
            $table->foreign('to')
                ->references('ISO')
                ->on('currencies')->onDelete('cascade');

            $table->decimal('rate',14,6);
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
        Schema::dropIfExists('rates');
    }
}
