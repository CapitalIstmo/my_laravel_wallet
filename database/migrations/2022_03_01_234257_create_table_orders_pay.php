<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableOrdersPay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_pay', function (Blueprint $table) {
            $table->id();
            $table->integer('amount');
            $table->foreignId('transmitter_id');
            $table->foreignId('receiver_id');
            $table->string('status', 100)->default('SI');
            $table->timestamps();

            $table->foreign('transmitter_id')->references('id')->on('users');
            $table->foreign('receiver_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_pay');
    }
}
