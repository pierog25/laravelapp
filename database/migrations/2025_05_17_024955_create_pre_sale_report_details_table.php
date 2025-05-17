<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreSaleReportDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_sale_report_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pre_sale_report_id');
            $table->string('resource');
            $table->unsignedBigInteger('supplier_id');
            $table->double('cost', 10, 2);
            $table->timestamps();
            $table->boolean('status')->default(true);
            $table->foreign('pre_sale_report_id')->references('id')->on('pre_sale_reports');
            $table->foreign('supplier_id')->references('id')->on('suppliers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pre_sale_report_details');
    }
}
