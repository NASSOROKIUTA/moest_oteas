<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblApplicants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_applicants', function (Blueprint $table) {
           $table->uuid('id');
            $table->primary('id');
            $table->string('first_name',80)->nullable();
            $table->string('middle_name',80)->nullable();
            $table->string('last_name',80)->nullable();
            $table->date('dob');
            $table->string('gender',6);
            $table->string('registration_number',25);
            $table->string('year_graduated',4);
            $table->string('form_four_index',16);
            $table->string('year_certified',4);
            $table->string('email',50)->nullable();
            $table->string('mobile_number',15)->nullable();
            $table->integer('residence_id',false,true)->length(11)->unsigned()->nullable();
            $table->uuid('college_id')->nullable();
            $table->foreign('residence_id')->references('id')->on('tbl_residences');
			$table->integer('priority',false,true)->default(0)->length(1)->unsigned();
            $table->integer('sne',false,true)->length(1)->default(0);
            $table->integer('marital_id',false,true)->length(11)->unsigned()->nullable();
            $table->foreign('marital_id')->references('id')->on('tbl_maritals');
			$table->integer('department_id',false,true)->length(11)->unsigned()->nullable();
            $table->foreign('department_id')->references('id')->on('tbl_departments');
           $table->integer('status',false,true)->length(1)->default(1);
		   $table->integer('region_birth',false,true)->length(11)->unsigned()->nullable();
            $table->foreign('region_birth')->references('id')->on('tbl_regions');
            $table->foreign('college_id')->references('id')->on('tbl_colleges');
            $table->integer('occupation_id',false,true)->length(11)->unsigned()->nullable();
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
        Schema::dropIfExists('tbl_applicants');
    }
}
