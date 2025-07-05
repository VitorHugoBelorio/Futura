<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contratante extends Model
{
    protected $fillable = [
        'nome',
        'cnpj',
        'telefone',
        'email',
        'banco_dados',
    ];

    // relacionamento com funcionários que gerenciam este contratante
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_contratante');
    }

    // ao criar, a lógica de criação do banco e migrações roda no Controller
}
