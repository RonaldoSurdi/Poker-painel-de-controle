<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournament_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tournament_id')->unsigned()->nullable();
            $table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');

            $table->tinyInteger('card')->default(1)->comment('1-dez, 2-valete, 3-dama, 4-rei, 5-as, 6-curinga');
            $table->integer('qtd')->default(1);
            $table->string('premium',40);

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
        Schema::dropIfExists('tournament_cards');
    }
}
