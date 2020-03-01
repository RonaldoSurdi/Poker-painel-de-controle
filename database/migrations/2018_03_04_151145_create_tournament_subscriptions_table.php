<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournament_subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tournament_id')->unsigned()->nullable();
            $table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');

            $table->integer('user_app_id')->unsigned()->nullable();
            $table->foreign('user_app_id')->references('id')->on('user_app')->onDelete('cascade');

            $table->integer('tournament_card_id')->unsigned()->nullable();
            $table->foreign('tournament_card_id')->references('id')->on('tournament_cards')->onDelete('cascade');

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
        Schema::dropIfExists('tournament_subscriptions');
    }
}
