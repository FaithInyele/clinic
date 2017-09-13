<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('other_names');
            $table->string('gender');
            $table->date('yob');
            $table->integer('weight');
            $table->integer('length');
            $table->string('county');
            $table->string('sub-county');
            $table->string('town');
            $table->string('vilage');
            $table->string('mothers_name')->nullable();
            $table->integer('mothers_id_no')->nullable();
            $table->integer('mothers_telephone_no')->nullable();
            $table->string('fathers_name')->nullable();
            $table->integer('fathers_id_no')->nullable();
            $table->integer('fathers_telephone_no')->nullable();
            $table->string('address')->nullable();

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
        Schema::dropIfExists('clients');
    }
}
