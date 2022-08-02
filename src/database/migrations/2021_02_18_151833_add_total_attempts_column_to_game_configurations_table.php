<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalAttemptsColumnToGameConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_configurations', function (Blueprint $table) {
            $table->integer('total_attempts')->after('amount_of_balls_to_fire');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_configurations', function (Blueprint $table) {
            $table->dropColumn('total_attempts');
        });
    }
}
