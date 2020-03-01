<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRankingPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ranking_points', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('player_id')->unsigned()->nullable();
            $table->foreign('player_id')->references('id')->on('ranking_players')->onDelete('cascade');

            $table->integer('point')->default(0);
            $table->integer('step')->default(1);

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
        Schema::dropIfExists('ranking_points');
    }
}
