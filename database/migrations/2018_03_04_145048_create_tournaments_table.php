<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('club_id')->unsigned()->nullable();
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');

            $table->string('name',50);

            $table->tinyInteger('type')->default(1)->comment('1-Semanal 2-agendado');
            $table->integer('week')->nullable();
            $table->time('week_hour')->nullable();

            $table->tinyInteger('qtd_days')->default(1);
            $table->text('desc')->nullable();
            $table->text('ring_game')->nullable();

            $table->string('img_ext',3)->nullable();

            $table->tinyInteger('insc_app')->default(0)->comment('0-nao 1-sim');
            $table->tinyInteger('promo')->default(0)->comment('0-nao 1-sim');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tournaments');
    }
}
