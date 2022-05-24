<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique()->nullable();
            $table->text('desc')->nullable();
            $table->text('spec')->nullable();
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(0);
            $table->foreignId('user_id')->default(1);
            $table->foreignId('unit_id')->nullable();
            $table->double('per_unit')->nullable();
            $table->foreignId('brand_id')->nullable();
            $table->boolean('disabled')->default(0);
            $table->integer('priority')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('item_type', function (Blueprint $table) {
            $table->foreignId('item_id');
            $table->foreignId('type_id');

            $table->primary(['item_id', 'type_id'], 'item_type_id');

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('skus', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable();
            $table->string('item_name')->nullable();
            $table->text('data')->nullable();
            $table->foreignId('item_id');
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(0);
            $table->unsignedDouble('pure_price')->nullable();
            $table->double('buy_price')->default(0);
            $table->foreignId('currency_id')->nullable();
            $table->boolean('disabled')->default(0);
            $table->integer('priority')->default(0);
            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('currency_id')->references('id')->on('currencies');
        });

        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('item_id');
            $table->foreignId('parent_id')->default(0);

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('values', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('attribute_id');

            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade')->onDelete('cascade');
        });

        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id');
            $table->foreignId('sku_id');
            $table->foreignId('attribute_id');
            $table->foreignId('value_id');

            $table->unique(['item_id', 'sku_id', 'attribute_id', 'value_id'], 'variant_primary');

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('sku_id')->references('id')->on('skus')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('value_id')->references('id')->on('values')->onDelete('cascade')->onUpdate('cascade');
        });

        //ဝယ်ရင်းဈေးမှာ cost ဘယ်လောက်ကျတယ် ဘာညာ အတွက်
        Schema::create('costs', function (Blueprint $table) {
            $table->id();
            $table->unsignedDouble('rate')->nullable();
            $table->unsignedDouble('amt');
            $table->foreignId('currency_id');
            $table->string('type')->default('cost');
            $table->timestamps();

            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('wastes', function (Blueprint $table) {
            $table->id();
            $table->unsignedDouble('amt');
            $table->foreignId('status_id');
            $table->text('remark')->nullable();
            $table->string('type')->default('waste');
            $table->date('date')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('sku_cost', function (Blueprint $table) {
            $table->foreignId('sku_id');
            $table->foreignId('cost_id');

            $table->primary(['sku_id', 'cost_id'], 'sku_cost_id');

            $table->foreign('sku_id')->references('id')->on('skus')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('cost_id')->references('id')->on('costs')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('sku_waste', function (Blueprint $table) {
            $table->foreignId('sku_id');
            $table->foreignId('waste_id');

            $table->primary(['sku_id', 'waste_id'], 'sku_waste_id');

            $table->foreign('sku_id')->references('id')->on('skus')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('waste_id')->references('id')->on('wastes')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('pricings', function (Blueprint $table) {
            $table->id();
            $table->unsignedDouble('amt');
            $table->foreignId('status_id');
            $table->integer('min_qty');
            $table->integer('max_qty');
            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('sku_pricing', function (Blueprint $table) {
            $table->foreignId('sku_id');
            $table->foreignId('pricing_id');

            $table->primary(['sku_id', 'pricing_id'], 'sku_pricing_id');

            $table->foreign('sku_id')->references('id')->on('skus')->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('pricing_id')->references('id')->on('pricings')->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('discountypes', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('desc')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedDouble('amt')->nullable();
            $table->foreignId('status_id')->nullable();
            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedDouble('amt');
            $table->foreignId('status_id');
            $table->date('expired')->nullable();
            $table->foreignId('discountype_id')->default(0);
            $table->foreignId('role_id');
            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('sku_discount', function (Blueprint $table) {
            $table->foreignId('discount_id');
            $table->foreignId('sku_id');

            $table->primary(['discount_id', 'sku_id'], 'sku_discount_key');

            $table->foreign('sku_id')->references('id')->on('skus')->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('item_discount', function (Blueprint $table) {
            $table->foreignId('discount_id');
            $table->foreignId('item_id');

            $table->primary(['discount_id', 'item_id'], 'item_discount_key');

            $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->double('value')->nullable();
            $table->double('percent_off')->nullable();
            $table->string('type')->default('fixed');
            $table->boolean('is_used')->default(0);
            $table->date('expired')->nullable();
            $table->foreignId('type_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->timestamps();
        });

        Schema::create('bonuspoints', function (Blueprint $table) {
            $table->id();
            $table->double('amt')->default(0);
            $table->integer('points')->default(0);
            $table->foreignId('role_id');
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('userpoints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->integer('points');
            $table->foreignId('status_id');
            $table->text('data')->nullable();
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
