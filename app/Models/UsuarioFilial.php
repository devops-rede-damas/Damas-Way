<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioFilial extends Model
{
    protected $table = 'usuario_filial';

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'modificado_em';

    protected $fillable = [
        'id_usuario',
        'id_coligada',
        'id_filial',
        'status',
        'criado_por',
        'modificado_por',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
