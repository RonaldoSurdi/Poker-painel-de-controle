<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClubsIndicadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clubs_indicados', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',150);
            $table->string('responsible',30);
            $table->string('phone',30);

            $table->tinyInteger('status')->default(0)->comment('0-Pendente 1-Cadastrado 2-Não Encontrado 3-Discartado');

            $table->integer('userapp_id')->unsigned()->nullable();
            $table->foreign('userapp_id')->references('id')->on('user_app')->onDelete('cascade');

            $table->integer('club_id')->nullable();
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
        Schema::dropIfExists('clubs_indicados');
    }
}
