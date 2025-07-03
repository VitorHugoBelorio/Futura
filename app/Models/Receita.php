<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receita extends Model
{
    // protected $table = 'receitas'; <- Revisar se é útil
    protected $fillable = [
        'descricao',
        'valor',
        'data',
        'contratante_id', // Chave estrangeira para Contratante
    ];

    public function contratante()
    {
        return $this->belongsTo(Contratante::class);
    }
}
