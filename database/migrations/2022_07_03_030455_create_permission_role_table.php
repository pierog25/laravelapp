<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_role', function (Blueprint $table) {
            $table->bigInteger('permission_id')->unsigned()->nullable();
            $table->foreign('permission_id')
                ->references('id')
                ->on('permission')
                ->onDelete('cascade');
            $table->bigInteger('role_id')->unsigned()->nullable();
            $table->foreign('role_id')
                ->references('id')
                ->on('role')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission_role');
    }
}
