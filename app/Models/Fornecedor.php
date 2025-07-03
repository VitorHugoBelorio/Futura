<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    // protected $table = 'fornecedores'; <- Revisar se é útil
    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'endereco',
        'documento', // Pode ser CPF ou CNPJ
    ];

    public function despesas()
    {
        return $this->hasMany(Despesa::class);
    }
}
