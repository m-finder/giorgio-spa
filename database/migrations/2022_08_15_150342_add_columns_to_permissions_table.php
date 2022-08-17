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
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('name_zh_cn')->after('name')->comment('权限中文名')->nullable(false);
            $table->string('method')->after('name_zh_cn')->comment('请求方式')->nullable(false);
            $table->string('uri')->after('method')->comment('uri')->nullable(false);
            $table->string('type')->after('guard_name')->comment('权限类型')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('name_zh_cn');
            $table->dropColumn('method');
            $table->dropColumn('uri');
            $table->dropColumn('type');
        });
    }
};
