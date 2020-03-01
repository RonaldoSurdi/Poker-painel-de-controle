<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLicensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('club_id')->unsigned();
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
            //
            $table->tinyInteger('type')->default(1)->comment('1-anuidade 2-30dias');
            // $table->tinyInteger('qtd_month')->default(12)->comment('qtd de meses');
            $table->tinyInteger('status')->default(0)->comment('0-pendente 1-ativa 2-vencida 9-bloqueada');
            $table->double('value',15,2)->default(0);
            //
            $table->timestamp('start_date')->nullable()->comment('data em que foi ativada');;
            $table->timestamp('due_date')->nullable()->comment('data de vencimento');
            //
            $table->integer('user_id')->unsigned();
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
        Schema::dropIfExists('licenses');
    }
}
