<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'modificado_em';

    protected $fillable = [
        'hash_protocolo',
        'valor_total',
        'observacoes',
        'id_filial_destino',
        'status_pedido',
        'data_finalizacao',
        'codigo_rastreio',
        'id_transportadora',
        'status',
        'criado_por',
        'modificado_por',
    ];

    public function filialDestino()
    {
        return $this->belongsTo(Filial::class, 'id_filial_destino');
    }

    public function statusPedido()
    {
        return $this->belongsTo(StatusPedido::class, 'status_pedido');
    }

    public function transportadora()
    {
        return $this->belongsTo(Transportadora::class, 'id_transportadora');
    }

    public function itens()
    {
        return $this->hasMany(ItemPedido::class, 'id_pedido');
    }

    public function endereco()
    {
        return $this->hasOne(EnderecoPedido::class, 'id_pedido');
    }

    public function historicoStatus()
    {
        return $this->hasMany(HistoricoStatusPedido::class, 'id_pedido');
    }

    public function ocorrencias()
    {
        return $this->hasMany(Ocorrencia::class, 'id_pedido');
    }

    public function criadoPor()
    {
        return $this->belongsTo(Usuario::class, 'criado_por');
    }
}
