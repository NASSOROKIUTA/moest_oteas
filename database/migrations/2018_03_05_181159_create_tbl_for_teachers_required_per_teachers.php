<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblForTeachersRequiredPerTeachers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_projected_teachers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('school_id',20)->nullable();
            $table->foreign('school_id')->references('centre_number')->on('tbl_schools');
            $table->integer('stream',false,true)->length(9);
            $table->integer('periods',false,true)->length(9);       
            $table->integer('teachers_required',false,true)->length(9);
            $table->integer('class_grade',false,true)->length(11)->unsigned();
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
        Schema::dropIfExists('tbl_projected_teachers');
    }
}
