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
            $table->string('id_number')->unique();
            $table->string('reg_number')->unique();
            $table->string('gender');
            $table->string('yob');
            $table->string('type');
            $table->string('phone');
            $table->string('address')->nullable();
            $table->integer('reg_fee')->nullable();
            $table->string('email')->nullable();
            $table->string('keen_type')->nullable();
            $table->string('keen_name')->nullable();
            $table->string('keen_contact')->nullable();
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
