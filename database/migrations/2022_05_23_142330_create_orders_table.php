<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->integer('order_no')->nullable();
            $table->string('order_month')->nullable();
            $table->foreignId('customer_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('status_id');
            $table->foreignId('role_id')->nullable(); //pos နဲ့ဝယ်တဲ့အခါ merchant ဈေးနဲ့ ဝယ်လို့ရအောင်
            $table->string('type')->default('order');
            $table->unsignedDouble('price')->nullable();
            $table->unsignedDouble('discount_amt')->nullable();
            $table->foreignId('discount_status')->nullable();
            $table->double('debt')->default(0);
            $table->text('data')->nullable();
            $table->text('remark')->nullable(); // admin ကပေးတဲ့ remark
            $table->text('note')->nullable(); //customer ကပေးလိုက်တဲ့ remark
            $table->timestamps();
        });

        Schema::create('order_sku', function (Blueprint $table) {
            $table->foreignId('order_id');
            $table->foreignId('sku_id');
            $table->foreignId('status_id');
            $table->integer('qty');
            $table->unsignedDouble('price');
            $table->unsignedDouble('customized_price');
            $table->unsignedDouble('buy_price')->default(0);
            $table->unsignedDouble('margin')->default(0.0);

            $table->primary(['order_id', 'sku_id'], 'order_sku_id');

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('sku_id')->references('id')->on('skus')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('gifts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('points')->default(0);
            $table->string('stock')->nullable();
            $table->integer('priority')->default(0);
            $table->timestamps();
        });

        Schema::create('usergifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('gift_id');
            $table->foreignId('status_id');
            $table->timestamps();

            $table->foreign('gift_id')->references('id')->on('gifts')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade')->onUpdate('cascade');
        }); 

        Schema::create('gift_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gift_id');
            $table->foreignId('sku_id')->nullable();
            $table->foreignId('user_id');
            $table->integer('qty');
            $table->boolean('is_published')->default(0);
            $table->date('date');
            $table->text('remark')->nullable(); //sku ကို gift ပေးထာဆိုရင် ဘာလို့ ပေးတာလဲ ထည့်ဖို့
            $table->timestamps();
        });

        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->integer('inventory_no')->nullable();
            $table->string('inventory_month')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('supplier_id')->nullable();
            $table->date('date')->nullable();
            $table->boolean('is_published')->default(false);
            $table->double('debt')->default(0);
            $table->timestamps();
        });

        Schema::create('sku_inventories', function (Blueprint $table) {
            $table->foreignId('sku_id');
            $table->foreignId('inventory_id');
            $table->integer('qty');
            $table->double('rate');
            $table->foreignId('currency_id');
            $table->double('amount')->nullable();
            $table->text('remark')->nullable();

            $table->primary(['sku_id', 'inventory_id'], 'sku_inventory_key');

            $table->foreign('sku_id')->references('id')->on('skus')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('inventory_id')->references('id')->on('inventories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->double('amount')->default();
            $table->foreignId('status_id'); //for transaction in or out
            $table->foreignId('paymentype_id'); // payment type bank, kpay, etc..,
            $table->foreignId('user_id');
            $table->date('date');
            $table->date('next_date')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('paymentype_id')->references('id')->on('statuses')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('transactionables', function (Blueprint $table) {
            $table->foreignId('transaction_id');
            $table->foreignId('transactionable_id');
            $table->string('transactionable_type');

            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade')->onUpdate('cascade');
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
