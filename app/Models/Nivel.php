<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{
    protected $table = 'niveis';

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'modificado_em';

    protected $fillable = [
        'nivel',
        'status',
        'criado_por',
        'modificado_por',
    ];

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'nivel_id');
    }
}
