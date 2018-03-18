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
            $table->uuid('id');
            $table->primary('id');
            $table->string('name');
            $table->integer('loggedIn',false,true)->length(1);
            $table->string('ip',45)->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('mobile_number')->nullable();
            $table->string('gender')->nullable();
            $table->integer('user_type',false,true)->length(11)->unsigned();
            $table->foreign('user_type')->references('id')->on('tbl_proffesionals'); 
			$table->integer('council_id',false,true)->nullable()->length(11)->unsigned();
            $table->foreign('council_id')->references('id')->on('tbl_councils'); 			
			$table->string('applicant_id',20)->nullable();
            $table->text('api_token')->nullable();
        $table->foreign('applicant_id')->references('applicant_id')->on('tbl_applicants');
            $table->integer('department_id',false,true)->length(11)->nullable()->unsigned();
            $table->foreign('department_id')->references('id')->on('tbl_departments');
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
