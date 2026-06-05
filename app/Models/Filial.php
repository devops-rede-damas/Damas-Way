<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filial extends Model
{
    protected $table = 'filiais';

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'modificado_em';

    protected $fillable = [
        'id_rm',
        'id_coligada',
        'filial',
        'endereco',
        'status',
    ];

    protected $casts = [
        'endereco' => 'array',
    ];

    public function coligada()
    {
        return $this->belongsTo(Coligada::class, 'id_coligada');
    }

    public function produtos()
    {
        return $this->hasMany(Produto::class, 'id_filial');
    }
}
