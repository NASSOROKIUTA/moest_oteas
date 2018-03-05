<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblForSchools extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_schools', function (Blueprint $table) {
       $table->increments('id');
	   $table->string('centre_number',20)->index()->nullable();
       $table->integer('council_id',false,true)->length(11)->unsigned();
       $table->integer('class_rooms',false,true)->length(3);      
	   $table->foreign('council_id')->references('id')->on('tbl_councils');
	   $table->integer('department_id',false,true)->length(11)->unsigned();
	   $table->foreign('department_id')->references('id')->on('tbl_departments');
	   $table->integer('residence_id',false,true)->length(11)->nullable()->unsigned();
	   $table->foreign('residence_id')->references('id')->on('tbl_residences');
	   $table->integer('double_shift',false,true)->length(1)->default(0);
       $table->integer('school_level',false,true)->length(11)->unsigned();
       $table->foreign('school_level')->references('id')->on('tbl_school_levels');
	   $table->string('school_name',80)->nullable();
	   $table->integer('special_needs',false,true)->length(1)->default(0);
	   $table->integer('special_needs_type',false,true)->length(1)->nullable();
	   $table->foreign('special_needs_type')->references('id')->on('tbl_special_needs');

	 $table->integer('day_boarding',false,true)->length(1)->default(1);
	  $table->decimal('distance_km',10)->default(0);
	  $table->integer('teaching_language',false,true)->length(1)->default(1);
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
        Schema::dropIfExists('tbl_schools');
    }
}
