<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleryViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery_views', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_app_id')->unsigned()->nullable();
            $table->foreign('user_app_id')->references('id')->on('user_app')->onDelete('cascade');

            $table->integer('gallery_id')->unsigned()->nullable();
            $table->foreign('gallery_id')->references('id')->on('galleries')->onDelete('cascade');

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
        Schema::dropIfExists('gallery_views');
    }
}
