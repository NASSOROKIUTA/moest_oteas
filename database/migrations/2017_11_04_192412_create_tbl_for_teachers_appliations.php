<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblForTeachersAppliations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_choices', function (Blueprint $table) {
        $table->uuid('id');
        $table->primary('id');
		$table->string('applicant_id',20);
        $table->foreign('applicant_id')->references('applicant_id')->on('tbl_applicants');
        $table->integer('region_id',false,true)->length(11)->unsigned();
        $table->foreign('region_id')->references('id')->on('tbl_regions');
		$table->integer('priority',false,true)->length(1)->unsigned()->nullable();
		$table->integer('status',false,true)->length(1)->unsigned()->default(0);
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
        Schema::dropIfExists('tbl_choices');
    }
}
