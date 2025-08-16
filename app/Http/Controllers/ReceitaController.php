<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receita;
use Illuminate\Support\Facades\DB;

class ReceitaController extends Controller
{
    public function index()
    {
        try {
            $receitas = Receita::all();
            return view('receitas.index', compact('receitas'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar receitas: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            return view('receitas.create');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar formulário: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'descricao' => 'required|string',
                'valor' => 'required|numeric|min:0',
                'data' => 'required|date',
                'data_recebimento' => 'required|date',
            ]);

            Receita::create($request->only(['descricao', 'valor', 'data', 'data_recebimento']));

            return redirect()
                ->route('contratantes.show', session('contratante_id'))
                ->with('success', 'Receita criada com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao criar receita: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $receita = Receita::findOrFail($id);
            return view('receitas.edit', compact('receita'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar receita: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'descricao' => 'required|string',
                'valor' => 'required|numeric|min:0',
                'data' => 'required|date',
                'data_recebimento' => 'required|date',
            ]);

            $receita = Receita::findOrFail($id);
            $receita->update($request->only(['descricao', 'valor', 'data', 'data_recebimento']));

            return redirect()
                ->route('contratantes.show', session('contratante_id'))
                ->with('success', 'Receita atualizada com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar receita: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $receita = Receita::findOrFail($id);
            $receita->delete();

            return redirect()
                ->route('contratantes.show', session('contratante_id'))
                ->with('success', 'Receita excluída com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir receita: ' . $e->getMessage());
        }
    }
}
