<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblColleges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_colleges', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('college_reg_number',90);
            $table->string('college_name',180);
            $table->string('email',45)->nullable();
            $table->string('focalname',45)->nullable();
            $table->string('college_address',85)->nullable();
        $table->integer('education_level')->length(11)->unsigned()->nullable();
		$table->foreign('education_level')->references('id')->on('tbl_education_levels');
        $table->integer('ownership_status')->length(11)->unsigned()->nullable();
		$table->foreign('ownership_status')->references('id')->on('tbl_ownerships');
        $table->integer('registration_status')->length(11)->unsigned()->nullable();
		$table->foreign('registration_status')->references('id')->on('tbl_college_status_registrations');
        $table->integer('college_status')->length(11)->unsigned()->nullable();
		$table->foreign('college_status')->references('id')->on('tbl_college_types');
           
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
        Schema::dropIfExists('tbl_colleges');
    }
}
