<?php

namespace App\Http\Controllers;

use App\Models\Contratante;
use App\Models\Despesa;
use App\Models\Fornecedor;
use App\Models\Receita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class ContratanteController extends Controller
{
    public function index()
    {
        $contratantes = Contratante::all();
        return view('contratantes.index', compact('contratantes'));
    }

    public function create()
    {
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

        return redirect()->route('contratantes.index')
                         ->with('success', 'Contratante criado com sucesso e banco provisionado!');
    }

    public function show(Contratante $contratante)
    {
        session(['contratante_id' => $contratante->id]);
        config(['database.connections.tenant_temp.database' => $contratante->banco_dados]);

        try {
            DB::connection('tenant_temp')->getPdo();

            $receitas = (new \App\Models\Receita())
                ->setConnection('tenant_temp')
                ->newQuery()
                ->get();

            $despesas = (new \App\Models\Despesa())
                ->setConnection('tenant_temp')
                ->newQuery()
                ->with('fornecedor')
                ->get();

            $fornecedores = (new \App\Models\Fornecedor())
                ->setConnection('tenant_temp')
                ->newQuery()
                ->get();

        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao conectar com o banco do contratante.');
        }

        return view('contratantes.show', compact(
            'contratante', 'receitas', 'despesas', 'fornecedores'
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

        return redirect()->route('contratantes.index')
                         ->with('success', 'Contratante atualizado com sucesso!');
    }

    public function destroy(Contratante $contratante)
    {
        $contratante->delete();

        return redirect()->route('contratantes.index')
                         ->with('success', 'Contratante excluído com sucesso!');
    }
}
