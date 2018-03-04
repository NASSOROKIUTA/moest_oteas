<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblForApplicantsAttendances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_attendance_reports', function (Blueprint $table) {
            $table->increments('id');
			$table->uuid('applicant_id');
            $table->foreign('applicant_id')->references('id')->on('tbl_applicants');
			$table->text('applicant_image')->nullable();
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
        Schema::dropIfExists('tbl_attendance_reports');
    }
}
