<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'modificado_em';

    protected $fillable = [
        'categoria',
        'status',
        'criado_por',
        'modificado_por',
    ];

    public function produtos()
    {
        return $this->hasMany(Produto::class, 'id_categoria');
    }
}
