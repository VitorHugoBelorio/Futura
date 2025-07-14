<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContratanteController;
use App\Http\Controllers\ContratoAtivoController;
use App\Http\Controllers\ReceitaController;
use App\Http\Controllers\DespesaController;
use App\Http\Controllers\FornecedorController;
use App\Http\Middleware\UsarBancoDoContratante;

Route::resource('contratantes', ContratanteController::class);

Route::get('/selecionar-contratante', [ContratoAtivoController::class, 'index'])->name('selecionar.contratante');
Route::post('/selecionar-contratante', [ContratoAtivoController::class, 'definir'])->name('selecionar.contratante.definir');

Route::middleware([UsarBancoDoContratante::class])->group(function () {
    Route::resource('fornecedores', FornecedorController::class)->except(['index', 'show']);
    Route::resource('receitas', ReceitaController::class)->except(['index', 'show']);
    Route::resource('despesas', DespesaController::class)->except(['index', 'show']);
});
