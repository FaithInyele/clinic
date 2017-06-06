<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('in_patients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ticket_id')->unsigned();
            $table->integer('assigned_to')->unsigned()->nullable();
            $table->timestamps();

            //create table relationships
            $table->foreign('ticket_id')->references('id')->on('tickets');
            $table->foreign('assigned_to')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('in_patients');
    }
}
