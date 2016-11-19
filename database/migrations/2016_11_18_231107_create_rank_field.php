<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRankField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('menus', function($table) {
            $table->text('rank')->nullable();
        });

        Schema::table('sub_menus', function($table) {
            $table->text('rank')->nullable();
        });

        Schema::table('contents', function($table) {
            $table->text('rank')->nullable();
        });

        Schema::table('intros', function($table) {
            $table->text('rank')->nullable();
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

        Schema::table('menus', function($table) {
            $table->dropColumn('rank')->nullable();
        });

        Schema::table('sub_menus', function($table) {
            $table->dropColumn('rank')->nullable();
        });

        Schema::table('contents', function($table) {
            $table->dropColumn('rank')->nullable();
        });

        Schema::table('intros', function($table) {
            $table->dropColumn('rank')->nullable();
        });
    }
}
