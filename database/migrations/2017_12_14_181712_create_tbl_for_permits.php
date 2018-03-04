<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblForPermits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_permits', function (Blueprint $table) {
     $table->increments('id');
	 $table->integer('subject_id',false,true)->nullable()->length(11)->unsigned();
     $table->foreign('subject_id')->references('id')->on('tbl_teaching_subjects');
	 $table->string('gender',6);
     $table->integer('council_id',false,true)->length(11)->unsigned();
     $table->foreign('council_id')->references('id')->on('tbl_councils');
     $table->integer('permits',false,true)->length(5)->unsigned();
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
        Schema::dropIfExists('tbl_permits');
    }
}
