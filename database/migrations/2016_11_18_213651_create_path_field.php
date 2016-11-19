<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePathField extends Migration
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
            $table->text('path')->nullable();
        });

        Schema::table('sub_menus', function($table) {
            $table->text('path')->nullable();
        });

        Schema::table('contents', function($table) {
            $table->text('path')->nullable();
        });

        Schema::table('intros', function($table) {
            $table->text('path')->nullable();
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
            $table->dropColumn('path')->nullable();
        });

        Schema::table('sub_menus', function($table) {
            $table->dropColumn('path')->nullable();
        });

        Schema::table('contents', function($table) {
            $table->dropColumn('path')->nullable();
        });

        Schema::table('intros', function($table) {
            $table->dropColumn('path')->nullable();
        });
    }
}
