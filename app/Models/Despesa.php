<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Despesa extends Model
{
    // Não defina connection fixa aqui. A conexão será setada dinamicamente.
    protected $fillable = [
        'descricao',
        'valor',
        'data_pagamento',
        'fornecedor_id',
    ];

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class);
    }
}
