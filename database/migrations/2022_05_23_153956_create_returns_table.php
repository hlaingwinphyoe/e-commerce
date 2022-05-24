<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->date('date')->nullable();
            $table->text('remark')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->timestamps();
        });

        Schema::create('return_sku', function (Blueprint $table) {
            $table->foreignId('sku_id');
            $table->foreignId('return_id');
            $table->integer('qty');
            $table->double('price');
            $table->text('remark')->nullable();

            $table->primary(['sku_id', 'return_id'], 'return_sku_id');

            $table->foreign('sku_id')->references('id')->on('skus')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('return_id')->references('id')->on('returns')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('returns');
    }
}
