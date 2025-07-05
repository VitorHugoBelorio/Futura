<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receita extends Model
{
    protected $connection = 'tenant_temp';
    protected $fillable = [
        'descricao',
        'valor',
        'data_recebimento',
    ];
}
