<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblYearLimits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_year_limits', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('form_four_graduation_year',false,true)->length(4)->unsigned();
			$table->integer('college_graduation_year',false,true)->length(4)->unsigned();
   
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
        Schema::dropIfExists('tbl_year_limits');
    }
}
