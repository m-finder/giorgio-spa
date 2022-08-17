<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->comment('后台管理员表');
            $table->id();
            $table->string('name')->comment('用户名');
            $table->string('email')->nullable()->default(null)->comment('管理员邮箱')->unique();
            $table->string('phone')->comment('管理员手机号')->unique();
            $table->string('avatar')->nullable()->default(null)->comment('头像url');
            $table->string('password')->comment('管理员密码');
            $table->string('status')->default('enabled')->comment('启用状态(enabled:启用中;disabled:已停用)');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
};
