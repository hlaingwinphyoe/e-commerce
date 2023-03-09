<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('amount');
            $table->foreignId('type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable();
            $table->date('date');
            $table->string('reference_id')->nullable();
            $table->foreignId('user_id');
            $table->timestamps();
        });

        Schema::table('types', function (Blueprint $table) {
            $table->string('type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenses');
        Schema::table('types', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
