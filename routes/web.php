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
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ChatController;
use Illuminate\Http\Request;
use Prism\Prism\Prism;
use Prism\Prism\Enums\Provider;

Route::get('/', function () {
    return view('welcome');
});

// Página de login
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'loginAttempt'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rotas dos dashboards
Route::get('/gerente/dashboard', [GerenteController::class, 'dashboard'])
    ->name('gerentes.dashboard')
    ->middleware(['auth', 'prevent-back-history']);

Route::get('/funcionario/dashboard', [FuncionarioController::class, 'dashboard'])
    ->name('funcionarios.dashboard')
    ->middleware(['auth', 'prevent-back-history']);

// Rota pública para selecionar contratante
Route::get('/selecionar-contratante', [ContratoAtivoController::class, 'index'])->name('selecionar.contratante');
Route::post('/selecionar-contratante', [ContratoAtivoController::class, 'definir'])->name('selecionar.contratante.definir');



// Rotas autenticadas sem banco tenant
Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    Route::get('/gerente/funcionarios', [GerenteController::class, 'funcionarios'])->name('funcionarios.index');
});

// Rotas autenticadas COM banco tenant
Route::middleware(['auth', 'prevent-back-history', UsarBancoDoContratante::class])->group(function () {
    Route::resource('fornecedores', FornecedorController::class)->except(['index', 'show']);
    Route::resource('receitas', ReceitaController::class);
    Route::resource('despesas', DespesaController::class);
    Route::get('/dashboard', [DashboardContratanteController::class, 'index'])->name('contratante.dashboard');
});

// Gerente
Route::middleware(['auth', 'gerente', 'prevent-back-history'])->prefix('gerente')->group(function () {
    Route::resource('funcionarios', FuncionarioController::class)->only([
        'index', 'create', 'store', 'edit', 'update', 'destroy'
    ]);
    Route::resource('gerentes', GerenteController::class)->only([
        'index', 'create', 'store', 'edit', 'update', 'destroy'
    ]);
    Route::delete('/gerentes/{id}', [GerenteController::class, 'destroy'])->name('gerentes.destroy');
});

// Rota para gerar relatório do mês referente ao contratante
Route::get('/contratante/relatorio/pdf', [DashboardContratanteController::class, 'gerarRelatorioPdf'])
    ->name('contratante.relatorio.pdf');

// Recuperar senha
Route::get('forgot-password', [ResetPasswordController::class, 'showForgotForm'])->name('password.request');
Route::post('forgot-password', [ResetPasswordController::class, 'sendResetLink'])->name('password.email');

Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.update');

// Funcionario desativados
Route::get('/funcionarios/desativados', [FuncionarioController::class, 'desativados'])->name('funcionarios.desativados');
Route::post('/funcionarios/{id}/reativar', [FuncionarioController::class, 'reativar'])->name('funcionarios.reativar');

// Gerentes desativados
Route::get('/gerentes/desativados', [GerenteController::class, 'desativados'])->name('gerentes.desativados');
Route::post('/gerentes/{id}/reativar', [GerenteController::class, 'reativar'])->name('gerentes.reativar');

// Contratantes desativados
Route::get('/contratantes/desativados', [ContratanteController::class, 'desativados'])->name('contratantes.desativados');
Route::post('/contratantes/{id}/reativar', [ContratanteController::class, 'reativar'])->name('contratantes.reativar');

// Contratantes (sem tenant middleware) -> ele não pode estar antes das rotas que trazem os contratantes desativados.
Route::resource('contratantes', ContratanteController::class);

// Rotas para o chat com IA
Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
Route::post('/chat', [ChatController::class, 'send'])->name('chat.send');
