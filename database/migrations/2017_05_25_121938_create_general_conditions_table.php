<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_conditions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('nurse_station_resource_id')->unsigned();
            $table->integer('ticket_id')->unsigned();
            $table->string('result');
            $table->timestamps();

            //create table relationships
            $table->foreign('nurse_station_resource_id')->references('id')->on('general_condition_resources');
            $table->foreign('ticket_id')->references('id')->on('tickets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('general_conditions');
    }
}
