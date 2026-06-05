<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coligada extends Model
{
    protected $table = 'coligadas';

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'modificado_em';

    protected $fillable = [
        'id_rm',
        'coligada',
        'status',
    ];

    public function filiais()
    {
        return $this->hasMany(Filial::class, 'id_coligada');
    }
}
