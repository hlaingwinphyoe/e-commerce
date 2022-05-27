<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('type')->default('Operation');
            $table->integer('priority')->nullable()->default(0);
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('type')->default('setting');
            $table->boolean('disabled')->default(0);
            $table->timestamps();
        });

        Schema::create('role_permission', function (Blueprint $table) {
            $table->foreignId('role_id');
            $table->foreignId('permission_id');

            $table->primary(['role_id', 'permission_id']);

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->unique()->nullable();
            $table->foreignId('role_id');
            $table->boolean('subscribed')->default(false);
            $table->integer('points')->default(0);

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('mm_name')->nullable();
            $table->text('desc')->nullable();
            $table->boolean('disabled')->default(0);
        });

        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('mm_name')->nullable();
            $table->foreignId('country_id');
            $table->text('desc')->nullable();
            $table->boolean('disabled')->default(0);
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade')->onUpdate('cascade');
        });


        Schema::create('townships', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('mm_name')->nullable();
            $table->text('desc')->nullable();
            $table->foreignId('region_id');
            $table->boolean('disabled')->default(0);

            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('mm_name')->nullable();
            $table->text('desc')->nullable();
            $table->foreignId('township_id');
            $table->boolean('disabled')->default(0);

            $table->foreign('township_id')->references('id')->on('townships')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->foreignId('country_id')->nullable();
            $table->foreignId('region_id')->nullable();
            $table->foreignId('addressable_id');
            $table->string('addressable_type');
            $table->timestamps();

            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique()->nullable();
            $table->timestamps();
        });

        Schema::create('medias', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('title')->nullable();
            $table->string('url');
            $table->string('ext')->nullable();
            $table->string('type')->default('images');
            $table->integer('priority')->default(0);
            $table->boolean('is_check')->default(0);
            $table->timestamps();
        });

        Schema::create('mediabbles', function (Blueprint $table) {
            $table->foreignId('media_id');
            $table->foreignId('mediabble_id');
            $table->string('mediabble_type');

            $table->primary(['media_id', 'mediabble_id', 'mediabble_type'], 'media_id_mediabble_id_type');

            $table->foreign('media_id')->references('id')->on('medias')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('mainfeatures', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('desc')->nullable();
            $table->string('link')->nullable();
            $table->string('type')->default('home');
            $table->boolean('disabled')->default(0);
            $table->integer('priority')->default(0);
            $table->timestamps();
        });

        Schema::create('types', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('desc')->nullable();
            $table->boolean('disabled')->default(0);
            $table->integer('priority')->default(0);
            $table->foreignId('parent_id')->default(0);
            $table->foreignId('user_id');
            $table->timestamps();
        });

        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('desc')->nullable();
            $table->boolean('disabled')->default(0);
            $table->foreignId('user_id');
            $table->timestamps();
        }); 

        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('desc')->nullable();
            $table->string('type')->default('status');
            $table->integer('priority')->default(0);
            $table->timestamps();
        });

        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('exchangerates', function (Blueprint $table) {
            $table->id();
            $table->unsignedDouble('rate');
            $table->unsignedDouble('mmk');
            $table->foreignId('currency_id');
            $table->foreignId('user_id');
            $table->timestamps();

            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('faq_types', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->text('name');
            $table->foreignId('user_id');
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('desc')->nullable();
            $table->foreignId('faq_type_id')->nullable();
            $table->integer('priority')->default(0);
            $table->timestamps();
        });

        Schema::create('units', function(Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->foreignId('parent_id')->default(0);
            $table->double('rate')->nullable(); //for unit rate e.g 1kg = 1000g (So rate of g is 1000 and parent_id is kg id)
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
        Schema::dropIfExists('roles');
    }
}
