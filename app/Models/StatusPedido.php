<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusPedido extends Model
{
    protected $table = 'status_pedido';

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'modificado_em';

    protected $fillable = [
        'nome_status',
        'descricao',
        'status',
        'criado_por',
        'modificado_por',
    ];
}
