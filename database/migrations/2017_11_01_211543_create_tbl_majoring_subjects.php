<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMajoringSubjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_majoring_subjects', function (Blueprint $table) {
        $table->uuid('id');
        $table->primary('id');
		$table->integer('subject_id',false,true)->length(11)->unsigned()->nullable();
        $table->foreign('subject_id')->references('id')->on('tbl_teaching_subjects');   
		$table->uuid('applicant_id')->nullable();
        $table->foreign('applicant_id')->references('id')->on('tbl_applicants');    
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
        Schema::dropIfExists('tbl_majoring_subjects');
    }
}
