<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receita;
use Illuminate\Support\Facades\DB;

class ReceitaController extends Controller
{
    public function index()
    {
        $receitas = Receita::all();
        return view('receitas.index', compact('receitas'));
    }

    public function create()
    {
        return view('receitas.create');
    }

    public function store(Request $request)
    {
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
    }

    public function edit($id)
    {
        $receita = Receita::findOrFail($id);
        return view('receitas.edit', compact('receita'));
    }

    public function update(Request $request, $id)
    {
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
    }

    public function destroy($id)
    {
        $receita = Receita::findOrFail($id);
        $receita->delete();

        return redirect()
            ->route('contratantes.show', session('contratante_id'))
            ->with('success', 'Receita excluída com sucesso!');
    }
}
