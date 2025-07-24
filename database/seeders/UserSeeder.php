<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nome' => 'Admin Gerente',
            'email' => 'gerente@futura.com',
            'password' => Hash::make('senha123'),
            'perfil' => 'root',
        ]);

        User::create([
            'nome' => 'Colaborador 1',
            'email' => 'funcionario@futura.com',
            'password' => Hash::make('senha123'),
            'perfil' => 'funcionario',
        ]);
    }
}