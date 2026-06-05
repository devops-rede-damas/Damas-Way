<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ocorrencia_img', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_ocorrencia');
            $table->string('path');
            $table->integer('status')->default(1)->comment('1 = Ativo | 2 = Inativo');
            $table->timestamp('criado_em')->useCurrent();
            $table->unsignedInteger('criado_por');
            $table->timestamp('modificado_em')->nullable();
            $table->unsignedInteger('modificado_por')->nullable();

            $table->foreign('id_ocorrencia')->references('id')->on('ocorrencias');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ocorrencia_img');
    }
};
