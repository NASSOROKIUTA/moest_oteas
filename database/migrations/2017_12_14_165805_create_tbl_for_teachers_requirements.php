<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblForTeachersRequirements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
     Schema::create('tbl_teachers_requirements', function (Blueprint $table) {
        $table->increments('id');
	    $table->string('school_id',20)->nullable();
        $table->foreign('school_id')->references('centre_number')->on('tbl_schools');
        $table->integer('students_taking',false,true)->length(11)->unsigned();
        $table->integer('teachers_available',false,true)->length(11)->unsigned();      
        $table->integer('school_level',false,true)->nullable()->length(11)->unsigned();
        $table->foreign('school_level')->references('id')->on('tbl_school_levels');
        $table->integer('subject_id',false,true)->nullable()->length(11)->unsigned();
        $table->foreign('subject_id')->references('id')->on('tbl_teaching_subjects');
		$table->integer('class_grade',false,true)->nullable()->length(11)->unsigned();
        $table->foreign('class_grade')->references('id')->on('tbl_class_grades');   
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
        Schema::dropIfExists('tbl_teachers_requirements');
    }
}
