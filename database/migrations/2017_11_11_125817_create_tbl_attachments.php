<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_attachments', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('describtion',60);
            $table->string('file_path',50);
           $table->string('applicant_id',20);
        $table->foreign('applicant_id')->references('applicant_id')->on('tbl_applicants');
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
        Schema::dropIfExists('tbl_attachments');
    }
}
