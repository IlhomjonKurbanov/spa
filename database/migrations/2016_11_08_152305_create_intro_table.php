<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('intros', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title')->nullable();
            $table->text('content')->nullable();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->string('name')->nullable();
            $table->string('main')->nullable();
            $table->text('description')->nullable();
            $table->integer('status')->unsigned()->default(1);
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
        Schema::drop('intros');
    }
}
