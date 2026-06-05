<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('chapa');
            $table->string('nome');
            $table->string('email');
            $table->string('senha');
            $table->rememberToken();
            $table->unsignedInteger('nivel_id');
            $table->integer('status')->default(1)->comment('1 = Ativo | 2 = Inativo');
            $table->timestamp('criado_em')->useCurrent();
            $table->unsignedInteger('criado_por');
            $table->timestamp('modificado_em')->nullable();
            $table->unsignedInteger('modificado_por')->nullable();

            $table->foreign('nivel_id')->references('id')->on('niveis');
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
};
