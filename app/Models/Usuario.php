<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'modificado_em';

    protected $fillable = [
        'chapa',
        'nome',
        'email',
        'senha',
        'nivel_id',
        'status',
        'criado_por',
        'modificado_por',
    ];

    protected $hidden = [
        'senha',
    ];

    public function getAuthPassword()
    {
        return $this->senha;
    }

    public function nivel()
    {
        return $this->belongsTo(Nivel::class, 'nivel_id');
    }

    public function filiais()
    {
        return $this->hasMany(UsuarioFilial::class, 'id_usuario');
    }

    public function isSuperAdmin(): bool
    {
        return mb_strtolower($this->nivel->nivel ?? '') === 'super administrador';
    }

    public function isAdmin(): bool
    {
        return mb_strtolower($this->nivel->nivel ?? '') === 'administrador';
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'criado_por');
    }
}
