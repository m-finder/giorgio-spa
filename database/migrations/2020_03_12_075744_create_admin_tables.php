<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminTables extends Migration
{

    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('alias');
            $table->timestamps();
        });

        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('role_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('api_token')->unique();
            $table->string('avatar');
            $table->timestamps();
        });

        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('parent_id');
            $table->string('name');
            $table->string('title');
            $table->string('redirect')->nullable()->default(null);
            $table->string('component');
            $table->string('path');
            $table->string('icon');
            $table->integer('order_num')->default(0);
            $table->boolean('hidden')->nullable()->default(false);
            $table->boolean('affix')->nullable()->default(false);
            $table->timestamps();
        });

        Schema::create('elements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('menu_id');
            $table->string('name');
            $table->string('code');
            $table->string('method');
            $table->string('path');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('role_menus', function (Blueprint $table) {
            $table->integer('role_id')->index();
            $table->integer('permission_id')->index();
        });

        Schema::create('role_elements', function (Blueprint $table) {
            $table->integer('role_id')->index();
            $table->integer('element_id')->index();
        });

        Schema::create('codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->index();
            $table->string('code')->index();
            $table->integer('is_used')->nullable()->default(0);
            $table->timestamps();
        });

        Schema::create('admin_api_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("user", 255);
            $table->text("request_url");
            $table->text("request_content");
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
        Schema::dropIfExists('admins');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('elements');
        Schema::dropIfExists('role_menus');
        Schema::dropIfExists('role_elements');
        Schema::dropIfExists('codes');
        Schema::dropIfExists('admin_api_logs');
    }
}