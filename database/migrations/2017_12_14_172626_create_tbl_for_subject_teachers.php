<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblForSubjectTeachers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_subjects_teachers', function (Blueprint $table) {
        $table->increments('id');
		$table->string('school_id',20)->nullable();
        $table->foreign('school_id')->references('centre_number')->on('tbl_schools');
        $table->decimal('teachers_number',11)->unsigned();
        $table->integer('subject_id',false,true)->nullable()->length(11)->unsigned();
        $table->foreign('subject_id')->references('id')->on('tbl_teaching_subjects');
		$table->string('academic_year',9);
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
        Schema::dropIfExists('tbl_subjects_teachers');
    }
}
