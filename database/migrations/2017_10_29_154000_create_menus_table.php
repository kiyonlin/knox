<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pid')->default(0);
            $table->string('key')->nullable()->comment('预留字段');
            $table->string('name');
            $table->string('path')->default('-');
            $table->unsignedSmallInteger('level')->default(1)->comment('菜单层级，辅助字段');
            $table->string('index')->nullable()->comment('菜单索引，辅助字段');
            $table->string('icon')->nullable();
            $table->boolean('is_leaf')->default(true);
            $table->unsignedInteger('sort')->nullable();
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
        Schema::dropIfExists('menus');
    }
}
