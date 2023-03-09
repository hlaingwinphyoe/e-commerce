<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->string('type')->default('inventory');
            $table->text('desc')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('desc');
        });
    }
}
