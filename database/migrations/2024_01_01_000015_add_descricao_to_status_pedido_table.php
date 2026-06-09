<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('status_pedido', function (Blueprint $table) {
            $table->string('descricao')->nullable()->after('nome_status');
        });
    }

    public function down()
    {
        Schema::table('status_pedido', function (Blueprint $table) {
            $table->dropColumn('descricao');
        });
    }
};
