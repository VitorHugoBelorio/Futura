<?php

use App\Http\Controllers\ContratanteController;
use App\Http\Controllers\ContratoAtivoController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\ReceitaController;
use App\Http\Controllers\DespesaController;
use App\Http\Middleware\UsarBancoDoContratante;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\GerenteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardContratanteController;

// Página de login
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'loginAttempt'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rotas dos dashboards
Route::get('/gerente/dashboard', [GerenteController::class, 'dashboard'])->name('gerentes.dashboard')->middleware('auth');
Route::get('/funcionario/dashboard', [FuncionarioController::class, 'dashboard'])->name('funcionarios.dashboard')->middleware('auth');

// Rota pública para selecionar contratante
Route::get('/selecionar-contratante', [ContratoAtivoController::class, 'index'])->name('selecionar.contratante');
Route::post('/selecionar-contratante', [ContratoAtivoController::class, 'definir'])->name('selecionar.contratante.definir');

// Contratantes (sem tenant middleware)
Route::resource('contratantes', ContratanteController::class);

// Rotas autenticadas sem banco tenant
Route::middleware('auth')->group(function () {
    Route::get('/gerente/funcionarios', [GerenteController::class, 'funcionarios'])->name('funcionarios.index');
});

// Rotas autenticadas COM banco tenant
Route::middleware(['auth', UsarBancoDoContratante::class])->group(function () {
    Route::resource('fornecedores', FornecedorController::class)->except(['index', 'show']);
    Route::resource('receitas', ReceitaController::class);
    Route::resource('despesas', DespesaController::class);
});


Route::middleware(['auth', 'gerente'])->prefix('gerente')->group(function () {
    Route::resource('funcionarios', FuncionarioController::class)->only([
        'index', 'create', 'store', 'edit', 'update', 'destroy'
    ]);
});

Route::middleware(['auth', UsarBancoDoContratante::class])->group(function () {
    Route::get('/dashboard', [DashboardContratanteController::class, 'index'])->name('contratante.dashboard');
});
