<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblForClassGrades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_class_grades', function (Blueprint $table) {
          $table->increments('id');
		  $table->string('grade',20)->nullable();
          $table->integer('school_level',false,true)->length(11)->unsigned();
          $table->foreign('school_level')->references('id')->on('tbl_school_levels');
          $table->integer('dept_id',false,true)->length(11)->unsigned();
	      $table->foreign('dept_id')->references('id')->on('tbl_departments');
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
        Schema::dropIfExists('tbl_class_grades');
    }
}
