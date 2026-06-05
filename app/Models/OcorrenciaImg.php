<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OcorrenciaImg extends Model
{
    protected $table = 'ocorrencia_img';

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'modificado_em';

    protected $fillable = [
        'id_ocorrencia',
        'path',
        'status',
        'criado_por',
        'modificado_por',
    ];

    public function ocorrencia()
    {
        return $this->belongsTo(Ocorrencia::class, 'id_ocorrencia');
    }
}
