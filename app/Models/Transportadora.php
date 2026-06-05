<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transportadora extends Model
{
    protected $table = 'transportadoras';

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'modificado_em';

    protected $fillable = [
        'nome',
        'api',
        'status',
        'criado_por',
        'modificado_por',
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'id_transportadora');
    }
}
