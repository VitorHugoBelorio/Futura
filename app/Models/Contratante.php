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
        'user_id',
    ];

    // relacionamento com funcionários que gerenciam este contratante
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_contratante');
    }

    // Define o relacionamento direto com a tabela Users mo banco de dados (para deixar as buscas mais rápidas)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // ao criar, a lógica de criação do banco e migrações roda no Controller
}
