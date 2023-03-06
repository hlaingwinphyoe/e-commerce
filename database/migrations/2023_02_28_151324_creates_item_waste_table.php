<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatesItemWasteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_waste', function (Blueprint $table) {
            $table->foreignId('item_id');
            $table->foreignId('waste_id');

            $table->primary(['item_id', 'waste_id'], 'item_waste_id');

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('waste_id')->references('id')->on('wastes')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('item_cost', function (Blueprint $table) {
            $table->foreignId('item_id');
            $table->foreignId('cost_id');

            $table->primary(['item_id', 'cost_id'], 'item_cost_id');

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('cost_id')->references('id')->on('costs')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('item_pricing', function (Blueprint $table) {
            $table->foreignId('item_id');
            $table->foreignId('pricing_id');

            $table->primary(['item_id', 'pricing_id'], 'item_pricing_id');

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('pricing_id')->references('id')->on('pricings')->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_waste');
        Schema::dropIfExists('item_cost');
        Schema::dropIfExists('item_pricing');
    }
}
