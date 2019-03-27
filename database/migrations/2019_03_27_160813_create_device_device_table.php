<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceDeviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_device', function (Blueprint $table) {
            $table->unsignedInteger('device_id');
            $table->unsignedInteger('neighbor_device_id');
            $table->primary(['device_id','neighbor_device_id']);
            $table->foreign('device_id')->references('id')->on('devices');
            $table->foreign('neighbor_device_id')->references('id')->on('devices');
            $table->integer('distance');
            $table->integer('available');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_device');
    }
}
