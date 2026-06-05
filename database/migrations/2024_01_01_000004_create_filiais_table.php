<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('filiais', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_rm');
            $table->unsignedInteger('id_coligada');
            $table->string('filial');
            $table->json('endereco');
            $table->integer('status')->default(1)->comment('1 = Ativo | 2 = Inativo');
            $table->timestamp('criado_em')->useCurrent();
            $table->timestamp('modificado_em')->nullable();

            $table->foreign('id_coligada')->references('id')->on('coligadas');
        });
    }

    public function down()
    {
        Schema::dropIfExists('filiais');
    }
};
