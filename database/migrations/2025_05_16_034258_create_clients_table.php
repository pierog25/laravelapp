<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_document_type');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('document_number', 20);
            $table->string('phone', 15);
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
            $table->boolean('status')->default(true);
            $table->foreign('id_document_type')->references('id')->on('document_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
