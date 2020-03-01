<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLicenseStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('license_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('license_id')->unsigned();
            $table->foreign('license_id')->references('id')->on('licenses')->onDelete('cascade');
            $table->tinyInteger('old')->default(0)->comment('0-pendente 1-ativa 2-vencida 9-bloqueada');
            $table->tinyInteger('new')->default(0)->comment('0-pendente 1-ativa 2-vencida 9-bloqueada');
            $table->string('text')->nullable();
            $table->integer('user_id')->unsigned();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('license_statuses');
    }
}
