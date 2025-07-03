<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Despesa extends Model
{
    // revisar se é necessário definir se foi pago ou não
    
    // protected $table = 'despesas'; <- Revisar se é útil
    
    protected $fillable = [
        'descricao',
        'valor',
        'data',
        'contratante_id', // Chave estrangeira para Contratante
        'fornecedor_id', // Chave estrangeira para fornecedor
    ];

    public function contratante()
    {
        return $this->belongsTo(Contratante::class);
    }
}
