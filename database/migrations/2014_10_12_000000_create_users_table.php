<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('id_no');
            $table->string('gender');
            $table->date('yob');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('activation_token')->nullable();
            $table->string('active'); //1 for active 0 for inactive
            $table->string('role'); //1 for receptionist 2 for lab-technician, 3 for chemist, 4 for nurse/doctor 5 for admin
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
