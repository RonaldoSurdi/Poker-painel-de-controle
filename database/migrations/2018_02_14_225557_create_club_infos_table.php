<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClubInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('club_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('club_id')->unsigned()->nullable();
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
            $table->tinyInteger('qtd_table')->default(1);
            $table->string('comfortable',50)->nullable();
            $table->string('social_facebook')->nullable();
            $table->string('social_instagran')->nullable();
            $table->string('link_live')->nullable();
            $table->string('desc',255)->nullable();
            $table->string('logo_ext',5)->nullable();
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
        Schema::dropIfExists('club_infos');
    }
}
