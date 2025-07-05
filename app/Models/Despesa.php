<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Despesa extends Model
{
    protected $connection = 'tenant_temp';
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
