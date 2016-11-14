<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('sub_menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('icon')->nullable();
            $table->string('icon_hover')->nullable();
            $table->string('main')->nullable();
            $table->text('description')->nullable();
            $table->integer('order')->unsigned()->default(1);
            $table->integer('status')->unsigned()->default(1);
            $table->integer('parent')->nullable();
            $table->integer('parent_type')->nullable();
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
        //
        Schema::drop('sub_menus');
    }
}
