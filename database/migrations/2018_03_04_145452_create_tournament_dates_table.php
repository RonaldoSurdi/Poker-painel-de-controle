<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournament_dates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tournament_id')->unsigned()->nullable();
            $table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');

            $table->timestamp('data');

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
        Schema::dropIfExists('tournament_dates');
    }
}
