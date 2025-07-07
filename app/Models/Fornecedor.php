<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    protected $connection = 'tenant_temp';
    protected $table = 'fornecedores'; 

    protected $fillable = [
        'nome',
        'cnpj',
        'telefone',
    ];

    public function despesas()
    {
        return $this->hasMany(Despesa::class);
    }
}
