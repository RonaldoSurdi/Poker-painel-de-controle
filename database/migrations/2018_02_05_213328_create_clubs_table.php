<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clubs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',100);
            $table->string('doc1',14)->nullable();
            $table->string('doc2',20)->nullable();

            $table->string('responsible',100)->nullable();
            $table->string('phone',15)->nullable();
            $table->string('whats',15)->nullable();
            $table->string('email',255)->nullable();
            $table->string('site',255)->nullable();

            $table->string('zipcode',9);
            $table->integer('city_id')->unsigned()->nullable();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->string('address');
            $table->string('number',10)->nullable();
            $table->string('district',50);
            $table->string('complement',30)->nullable();

            $table->float('lat',12,8)->default(0);
            $table->float('lng',12,8)->default(0);

            $table->tinyInteger('status')->default(0)->comment('0-bloqueado 1-liberado');
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
        Schema::dropIfExists('clubs');
    }
}
