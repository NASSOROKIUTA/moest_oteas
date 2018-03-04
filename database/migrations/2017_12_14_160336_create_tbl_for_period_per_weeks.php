<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblForPeriodPerWeeks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
Schema::create('tbl_periods_per_weeks', function (Blueprint $table) {
$table->increments('id');
$table->integer('number_of_periods',false,true)->length(8)->nullable();
$table->integer('teacher_work_load',false,true)->length(8)->default(30);
$table->integer('max_class_size',false,true)->length(8)->default(60);
$table->integer('minimum_teachers_required',false,true)->length(8)->default(7);
$table->integer('class_grade_id',false,true)->length(11)->unsigned();
$table->foreign('class_grade_id')->references('id')->on('tbl_class_grades');
$table->integer('subject_id',false,true)->nullable()->length(11)->unsigned();
$table->foreign('subject_id')->references('id')->on('tbl_teaching_subjects');
$table->integer('status',false,true)->length(1)->default(1);
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
        Schema::dropIfExists('tbl_periods_per_weeks');
    }
}
