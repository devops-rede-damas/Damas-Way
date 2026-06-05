<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('item_pedido', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_pedido');
            $table->unsignedInteger('id_produto');
            $table->decimal('valor_unitario', 10, 2);
            $table->integer('qtd');
            $table->decimal('valor_total', 10, 2);
            $table->integer('status')->default(1)->comment('1 = Ativo | 2 = Inativo');
            $table->timestamp('criado_em')->useCurrent();
            $table->unsignedInteger('criado_por');
            $table->timestamp('modificado_em')->nullable();
            $table->unsignedInteger('modificado_por')->nullable();

            $table->foreign('id_pedido')->references('id')->on('pedidos');
            $table->foreign('id_produto')->references('id')->on('produtos');
        });
    }

    public function down()
    {
        Schema::dropIfExists('item_pedido');
    }
};
