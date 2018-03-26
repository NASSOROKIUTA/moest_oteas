<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblForBarcode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_barcode_generators', function (Blueprint $table) {
            $table->increments('id');
			$table->string('applicant_id',20);
            $table->foreign('applicant_id')->references('applicant_id')->on('tbl_applicants');
    
            $table->string('barcode_no',25)->nullable();
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
        Schema::dropIfExists('tbl_barcode_generators');
    }
}
