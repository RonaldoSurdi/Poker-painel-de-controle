<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRankingPlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ranking_players', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ranking_id')->unsigned()->nullable();
            $table->foreign('ranking_id')->references('id')->on('rankings')->onDelete('cascade');

            $table->string('name',50);
            $table->string('photo_ext',5)->nullable();

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
        Schema::dropIfExists('ranking_players');
    }
}
