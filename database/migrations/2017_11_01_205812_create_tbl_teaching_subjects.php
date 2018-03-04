<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblTeachingSubjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_teaching_subjects', function (Blueprint $table) {
        $table->increments('id');
        $table->string('code',20)->nullable();
        $table->string('subject_name',70)->nullable();
		$table->integer('department_id',false,true)->length(11)->unsigned()->nullable();
        $table->foreign('department_id')->references('id')->on('tbl_departments');
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
        Schema::dropIfExists('tbl_teaching_subjects');
    }
}
