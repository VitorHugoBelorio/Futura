<?php

namespace App\Http\Controllers;

use App\Models\Contratante;
use App\Models\Despesa;
use App\Models\Fornecedor;
use App\Models\Receita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Carbon;

class ContratanteController extends Controller
{
    public function index()
    {
        $contratantes = Contratante::all();
        return view('contratantes.index', compact('contratantes'));
    }

    public function create()
    {
        /*
        if (!auth()->user()->isRoot()) {
            abort(403, 'Acesso não autorizado.');
        }
        */
        return view('contratantes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cnpj' => 'required|string|max:18|unique:contratantes',
            'email' => 'required|email|unique:contratantes',
            'telefone' => 'nullable|string|max:20',
        ]);

        $nomeBanco = 'empresa_' . strtolower(preg_replace('/\s+/', '_', $request->nome));

        DB::statement("CREATE DATABASE `$nomeBanco`");

        $contratante = Contratante::create([
            'nome' => $request->nome,
            'cnpj' => $request->cnpj,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'banco_dados' => $nomeBanco,
        ]);

        config(['database.connections.tenant_temp.database' => $nomeBanco]);

        Artisan::call('migrate', [
            '--path' => 'database/migrations/tenant',
            '--database' => 'tenant_temp',
            '--force' => true,
        ]);

        return redirect()->route('gerentes.dashboard')
                         ->with('success', 'Contratante criado com sucesso e banco provisionado!');
    }

    public function show(Contratante $contratante, Request $request)
    {
        session(['contratante_id' => $contratante->id]);
        config(['database.connections.tenant_temp.database' => $contratante->banco_dados]);

        try {
            DB::connection('tenant_temp')->getPdo();

            // Se houver filtro na requisição, usa ele. Senão, usa o mês atual
            $dataInicio = $request->input('data_inicio') ?? Carbon::now()->startOfMonth()->toDateString();
            $dataFim = $request->input('data_fim') ?? Carbon::now()->endOfMonth()->toDateString();

            $receitas = (new Receita())
                ->setConnection('tenant_temp')
                ->whereBetween('data_recebimento', [$dataInicio, $dataFim])
                ->get();

            $despesas = (new Despesa())
                ->setConnection('tenant_temp')
                ->whereBetween('data_pagamento', [$dataInicio, $dataFim])
                ->with('fornecedor')
                ->get();

            $fornecedores = (new Fornecedor())
                ->setConnection('tenant_temp')
                ->get();

            $saldo = $receitas->sum('valor') - $despesas->sum('valor');

        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao conectar com o banco do contratante: ' . $e->getMessage());
        }

        return view('contratantes.show', compact(
            'contratante',
            'receitas',
            'despesas',
            'fornecedores',
            'saldo',
            'dataInicio',
            'dataFim'
        ));
    }



    public function edit(Contratante $contratante)
    {
        return view('contratantes.edit', compact('contratante'));
    }

    public function update(Request $request, Contratante $contratante)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cnpj' => 'required|string|max:18|unique:contratantes,cnpj,' . $contratante->id,
            'email' => 'required|email|unique:contratantes,email,' . $contratante->id,
            'telefone' => 'nullable|string|max:20',
        ]);

        $contratante->update($request->all());

        // Redireciona para o dashboard do usuário logado
        if (auth()->user()->isGerente()) {
            return redirect()->route('gerentes.dashboard')
                ->with('success', 'Contratante atualizado com sucesso!');
        } else {
            return redirect()->route('funcionarios.dashboard')
                ->with('success', 'Contratante atualizado com sucesso!');
        }
    }

    public function destroy(Contratante $contratante)
    {
        try {
            // Armazena o nome do banco antes de deletar o contratante
            $nomeBanco = $contratante->banco_dados;

            // Remove o contratante da tabela principal
            $contratante->delete();

            // Executa a exclusão do banco de dados (após remover o registro)
            DB::statement("DROP DATABASE IF EXISTS `$nomeBanco`");

            return redirect()->route('gerentes.dashboard')
                            ->with('success', 'Contratante e banco de dados removidos com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('gerentes.dashboard')
                            ->with('error', 'Erro ao excluir contratante: ' . $e->getMessage());
        }
    }

}
