<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->date('delivery_date');
            $table->string('delivery_location');
            $table->enum('order_status', [
                'Por Cotizar',
                'Cotizado',
                'Aceptado',
                'En Producción',
                'Producido',
                'Enviado',
                'Cancelado'
            ]);
            $table->date('issue_date');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->boolean('status')->default(true);
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
