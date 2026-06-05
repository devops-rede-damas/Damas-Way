<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdutoImg extends Model
{
    protected $table = 'produto_img';

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'modificado_em';

    protected $fillable = [
        'id_produto',
        'path',
        'principal',
        'status',
        'criado_por',
        'modificado_por',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'id_produto');
    }
}
