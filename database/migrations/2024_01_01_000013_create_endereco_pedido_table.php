<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('endereco_pedido', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_pedido');
            $table->string('rua');
            $table->string('bairro');
            $table->string('cidade');
            $table->string('estado');
            $table->string('cep');
            $table->integer('status')->default(1)->comment('1 = Ativo | 2 = Inativo');
            $table->timestamp('criado_em')->useCurrent();
            $table->unsignedInteger('criado_por');
            $table->timestamp('modificado_em')->nullable();
            $table->unsignedInteger('modificado_por')->nullable();

            $table->foreign('id_pedido')->references('id')->on('pedidos');
        });
    }

    public function down()
    {
        Schema::dropIfExists('endereco_pedido');
    }
};
