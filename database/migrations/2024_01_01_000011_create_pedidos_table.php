<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hash_protocolo');
            $table->decimal('valor_total', 10, 2);
            $table->text('observacoes')->nullable();
            $table->unsignedInteger('id_filial_destino');
            $table->unsignedInteger('status_pedido');
            $table->timestamp('data_finalizacao')->nullable();
            $table->string('codigo_rastreio')->nullable();
            $table->unsignedInteger('id_transportadora')->nullable();
            $table->integer('status')->default(1)->comment('1 = Ativo | 2 = Inativo');
            $table->timestamp('criado_em')->useCurrent();
            $table->unsignedInteger('criado_por');
            $table->timestamp('modificado_em')->nullable();
            $table->unsignedInteger('modificado_por')->nullable();

            $table->foreign('id_filial_destino')->references('id')->on('filiais');
            $table->foreign('status_pedido')->references('id')->on('status_pedido');
            $table->foreign('id_transportadora')->references('id')->on('transportadoras');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
};
