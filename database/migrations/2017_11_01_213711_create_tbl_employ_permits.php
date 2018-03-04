<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblEmployPermits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_employ_permits', function (Blueprint $table) {
           $table->increments('id');
		   $table->integer('council_id',false,true)->length(11)->unsigned()->nullable();
           $table->foreign('council_id')->references('id')->on('tbl_councils');   
		   $table->integer('subject_id',false,true)->length(11)->unsigned()->nullable();
           $table->foreign('subject_id')->references('id')->on('tbl_teaching_subjects');
		   $table->integer('permit',false,true)->length(11);          
		   $table->string('gender',6)->nullable();          
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
        Schema::dropIfExists('tbl_employ_permits');
    }
}
