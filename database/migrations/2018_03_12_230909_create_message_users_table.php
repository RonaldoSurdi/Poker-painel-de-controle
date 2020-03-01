<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('message_id')->unsigned()->nullable();
            $table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade');
            $table->integer('user_app_id')->unsigned()->nullable();
            $table->foreign('user_app_id')->references('id')->on('user_app')->onDelete('cascade');

            $table->tinyInteger('status')->default(0)->comment('0-pendente 1-enviada 2-recebida 3-lida 9-erro');

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
        Schema::dropIfExists('message_users');
    }
}
