<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('produto');
            $table->unsignedInteger('id_categoria');
            $table->unsignedInteger('id_filial');
            $table->integer('qtd_estoque')->default(0);
            $table->decimal('valor', 10, 2);
            $table->integer('status')->default(1)->comment('1 = Ativo | 2 = Inativo');
            $table->timestamp('criado_em')->useCurrent();
            $table->unsignedInteger('criado_por');
            $table->timestamp('modificado_em')->nullable();
            $table->unsignedInteger('modificado_por')->nullable();

            $table->foreign('id_categoria')->references('id')->on('categorias');
            $table->foreign('id_filial')->references('id')->on('filiais');
        });
    }

    public function down()
    {
        Schema::dropIfExists('produtos');
    }
};
