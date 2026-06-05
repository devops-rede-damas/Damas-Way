<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoricoStatusPedido extends Model
{
    protected $table = 'historico_status_pedido';

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'modificado_em';

    protected $fillable = [
        'id_pedido',
        'id_status_anterior',
        'id_status_novo',
        'status',
        'criado_por',
        'modificado_por',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }

    public function statusAnterior()
    {
        return $this->belongsTo(StatusPedido::class, 'id_status_anterior');
    }

    public function statusNovo()
    {
        return $this->belongsTo(StatusPedido::class, 'id_status_novo');
    }
}
