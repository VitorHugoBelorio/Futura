<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contratante extends Model
{
    // protected $table = 'contratantes'; <- Revisar se é útil
    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'endereco',
        'documento', // Pode ser CPF ou CNPJ
    ];
}
