<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblForApplications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
     Schema::create('tbl_applications', function (Blueprint $table) {
     $table->increments('id');
     $table->string('school_id',20)->nullable();
     $table->foreign('school_id')->references('centre_number')->on('tbl_schools');
     $table->integer('subject_id',false,true)->length(11)->nullable()->unsigned();
     $table->foreign('subject_id')->references('id')->on('tbl_teaching_subjects');
     $table->uuid('applicant_id');
     $table->foreign('applicant_id')->references('id')->on('tbl_applicants');	 
	 $table->integer('priority',false,true)->length(1)->unsigned();
	 $table->integer('selected',false,true)->length(1)->default(0);
	 $table->integer('lock_applicant',false,true)->length(1)->default(0);
	 $table->integer('council_id',false,true)->length(11)->unsigned();
     $table->foreign('council_id')->references('id')->on('tbl_councils');
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
        Schema::dropIfExists('tbl_applications');
    }
}
