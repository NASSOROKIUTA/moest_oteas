<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblForTeachersRequiredPerSchool extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_school_requirements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('school_id',20)->nullable();
            $table->foreign('school_id')->references('centre_number')->on('tbl_schools');
            $table->decimal('ptr');
            $table->integer('required_teachers',false,true)->length(9);
            $table->integer('deficit_rooms',false,true)->length(9);
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
        Schema::dropIfExists('tbl_school_requirements');
    }
}
