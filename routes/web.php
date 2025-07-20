<?php

use App\Http\Controllers\ContratanteController;
use App\Http\Controllers\ContratoAtivoController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\ReceitaController;
use App\Http\Controllers\DespesaController;
use App\Http\Middleware\UsarBancoDoContratante;
use Illuminate\Support\Facades\Route;

// Contratantes principais (cadastram e listam contratantes)
Route::resource('contratantes', ContratanteController::class);

// Tela para selecionar o contratante ativo
Route::get('/selecionar-contratante', [ContratoAtivoController::class, 'index'])->name('selecionar.contratante');
Route::post('/selecionar-contratante', [ContratoAtivoController::class, 'definir'])->name('selecionar.contratante.definir');

// Rotas para gestão dos dados do contratante ativo 
Route::middleware([UsarBancoDoContratante::class])->group(function () {
    Route::resource('fornecedores', FornecedorController::class)->except(['index', 'show']);
    Route::resource('receitas', ReceitaController::class);
    Route::resource('despesas', DespesaController::class);
});
