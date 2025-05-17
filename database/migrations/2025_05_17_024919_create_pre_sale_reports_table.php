<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreSaleReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_sale_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_detail_id');
            $table->double('unit_price', 10, 2);
            $table->timestamps();
            $table->boolean('status')->default(true);
            $table->foreign('order_detail_id')->references('id')->on('order_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pre_sale_reports');
    }
}
