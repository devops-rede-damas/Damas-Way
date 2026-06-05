<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'produtos';

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'modificado_em';

    protected $fillable = [
        'produto',
        'id_categoria',
        'id_filial',
        'qtd_estoque',
        'valor',
        'status',
        'criado_por',
        'modificado_por',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function filial()
    {
        return $this->belongsTo(Filial::class, 'id_filial');
    }

    public function imagens()
    {
        return $this->hasMany(ProdutoImg::class, 'id_produto');
    }

    public function imagemPrincipal()
    {
        return $this->hasOne(ProdutoImg::class, 'id_produto')
            ->where('status', 1)
            ->orderByDesc('principal')
            ->orderBy('id');
    }
}
