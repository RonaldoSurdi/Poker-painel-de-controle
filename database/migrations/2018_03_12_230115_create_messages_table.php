<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('club_id')->unsigned()->nullable();
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');

            $table->string('title',50);
            $table->tinyInteger('msg_type')->default(1)->comment('1-text 2-image');
            $table->tinyInteger('user_type')->default(1)->comment('1-seguidores 2-raio 3-todos');
            $table->mediumInteger('radius')->default(0);

            $table->text('text')->nullable();
            $table->string('img_ext',5)->nullable();

            $table->tinyInteger('status')->default(0)->comment('0-pendente 1-agendada 2-enviada 3-erro 9-cancelada');
            $table->tinyInteger('approved')->default(0)->comment('0-nao 1-sim');
            $table->double('price',15,2)->default(0);

            $table->timestamp('date_send')->nullable();

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
        Schema::dropIfExists('messages');
    }
}
