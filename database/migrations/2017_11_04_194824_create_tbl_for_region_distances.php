<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblForRegionDistances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_region_distances', function (Blueprint $table) {
        $table->increments('id');
		$table->integer('region_id',false,true)->length(11)->unsigned();
        $table->foreign('region_id')->references('id')->on('tbl_regions');
		$table->integer('distance_to_dodoma',false,true)->length(8)->default(0);
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
        Schema::dropIfExists('tbl_region_distances');
    }
}
