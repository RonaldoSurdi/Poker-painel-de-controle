<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAppTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_app', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('senha',20)->nullable();
            $table->string('phone',20)->nullable();
            $table->date('birth_at')->nullable();
            $table->string('face_id',36)->nullable();
            $table->tinyInteger('confirmed')->default(0);
            $table->float('lat',12,8)->default(0);
            $table->float('lng',12,8)->default(0);
            $table->string('onesignal_uid',36)->nullable();
            $table->string('uid_web',36)->nullable();
            $table->timestamps();
            $table->softDeletes(); ///Colocar no model use SoftDeletes; protected $dates = ['deleted_at'];
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_app');
    }
}
