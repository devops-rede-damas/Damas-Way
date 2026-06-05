<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('coligadas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_rm');
            $table->string('coligada');
            $table->integer('status')->default(1)->comment('1 = Ativo | 2 = Inativo');
            $table->timestamp('criado_em')->useCurrent();
            $table->timestamp('modificado_em')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coligadas');
    }
};
