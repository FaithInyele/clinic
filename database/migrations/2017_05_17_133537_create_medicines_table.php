<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('prescription_id')->unsigned();
            $table->integer('chemist_resource_id')->unsigned();
            $table->string('alternatative')->nullable();
            $table->integer('amount')->nullable();
            $table->string('status');  //issued or external(client to buy prescription outside and return receipt for refund)
            $table->timestamps();

            //create table relationships
            $table->foreign('prescription_id')->references('id')->on('prescriptions');
            $table->foreign('chemist_resource_id')->references('id')->on('chemist_resources');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medicines');
    }
}
