<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('historico_status_pedido', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_pedido');
            $table->unsignedInteger('id_status_anterior');
            $table->unsignedInteger('id_status_novo');
            $table->integer('status')->default(1)->comment('1 = Ativo | 2 = Inativo');
            $table->timestamp('criado_em')->useCurrent();
            $table->unsignedInteger('criado_por');
            $table->timestamp('modificado_em')->nullable();
            $table->unsignedInteger('modificado_por')->nullable();

            $table->foreign('id_pedido')->references('id')->on('pedidos');
            $table->foreign('id_status_anterior')->references('id')->on('status_pedido');
            $table->foreign('id_status_novo')->references('id')->on('status_pedido');
        });
    }

    public function down()
    {
        Schema::dropIfExists('historico_status_pedido');
    }
};
