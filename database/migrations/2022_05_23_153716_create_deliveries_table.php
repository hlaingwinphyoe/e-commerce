<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) { // Royal, MGL အဲ့လို  Deli Company တွေ
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        Schema::create('delivery_order', function (Blueprint $table) {
            $table->foreignId('delivery_id');
            $table->foreignId('order_id');
            $table->date('date');
            $table->text('remark')->nullable();

            $table->foreign('delivery_id')->references('id')->on('deliveries')->onDelete('cascade')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade')->onUpdate('cascade');
        });

        //ဘယ်မြို့နယ်ဆို ဘယ်လောက်နဲ့ ပို့မယ် အဲ့လို
        Schema::create('deli_fees', function (Blueprint $table) {
            $table->id();
            $table->double('amt');
            $table->foreignId('user_id');
            $table->text('desc')->nullable();
            $table->timestamps();
        });

        Schema::create('deli_township', function (Blueprint $table) {
            $table->foreignId('deli_fee_id');
            $table->foreignId('township_id');

            $table->primary(['deli_fee_id', 'township_id'], 'deli_township_id');

            $table->foreign('deli_fee_id')->references('id')->on('deli_fees')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('township_id')->references('id')->on('townships')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
}
