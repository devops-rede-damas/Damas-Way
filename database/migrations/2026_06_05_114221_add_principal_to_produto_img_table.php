<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrincipalToProdutoImgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('produto_img', function (Blueprint $table) {
            $table->integer('principal')->default(0)->after('path')->comment('0 = Não | 1 = Principal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('produto_img', function (Blueprint $table) {
            $table->dropColumn('principal');
        });
    }
}
