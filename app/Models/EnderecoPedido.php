<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnderecoPedido extends Model
{
    protected $table = 'endereco_pedido';

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'modificado_em';

    protected $fillable = [
        'id_pedido',
        'rua',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'status',
        'criado_por',
        'modificado_por',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }
}
