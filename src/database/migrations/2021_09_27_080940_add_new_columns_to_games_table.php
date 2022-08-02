<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->integer('admin_percentage');
            $table->integer('admin_fee');
            $table->integer('profit');
            $table->integer('players_paid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('admin_percentage');
            $table->dropColumn('admin_fee');
            $table->dropColumn('profit');
            $table->dropColumn('players_paid');
        });
    }
}
