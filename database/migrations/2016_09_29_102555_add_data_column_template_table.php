<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataColumnTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('templates', function (Blueprint $table) {
            $table->mediumText('data');
        });

        Schema::table('topics', function (Blueprint $table) {
            $table->dropColumn('data');
        });

        Schema::table('topics', function (Blueprint $table) {
            $table->mediumText('data');
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
        Schema::table('gift_exchanges', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
