<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ticket_id')->unsigned();
            $table->integer('assigned_to')->unsigned();
            $table->string('status'); //-1 for pending payments 0 for finished payments 1 for finished tests(submitted)
            $table->integer('type');
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
        Schema::dropIfExists('lab_datas');
    }
}
