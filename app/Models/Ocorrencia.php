<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ocorrencia extends Model
{
    protected $table = 'ocorrencias';

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'modificado_em';

    protected $fillable = [
        'id_pedido',
        'descricao',
        'status',
        'criado_por',
        'modificado_por',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }

    public function imagens()
    {
        return $this->hasMany(OcorrenciaImg::class, 'id_ocorrencia');
    }
}
