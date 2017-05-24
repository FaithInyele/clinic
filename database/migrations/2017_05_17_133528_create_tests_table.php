<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lab_id')->unsigned();
            $table->integer('lab_resource_id')->unsigned();
            $table->string('result')->nullable();
            $table->integer('amount')->nullable();
            $table->timestamps();

            //create table relationships
            $table->foreign('lab_id')->references('id')->on('lab_datas');
            $table->foreign('lab_resource_id')->references('id')->on('lab_resources');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tests');
    }
}
