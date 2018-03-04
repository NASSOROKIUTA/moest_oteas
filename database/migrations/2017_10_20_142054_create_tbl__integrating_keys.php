<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblIntegratingKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_integrating_keys', function (Blueprint $table) {
            $table->uuid('id');
			$table->primary('id');
			$table->string('base_urls',80)->nullable();
			$table->string('private_keys',80)->nullable();
			$table->string('public_keys',80)->nullable();
			$table->integer('api_type')->length(3)->nullable();
			$table->integer('active')->length(1)->default(1);
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
        Schema::dropIfExists('tbl_integrating_keys');
    }
}
