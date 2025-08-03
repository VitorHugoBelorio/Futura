<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class GerenteSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'vitor@gmail.com'],
            [
                'nome'     => 'Vitor Hugo',
                'password' => Hash::make('123456'),
                'perfil'   => 'gerente',
            ]
        );
    }
}
