<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Despesa;
use App\Models\Fornecedor;

class DespesaController extends Controller
{
    public function create()
    {
        $fornecedores = Fornecedor::all();
        return view('despesas.create', compact('fornecedores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'descricao' => 'required|string',
            'valor' => 'required|numeric|min:0',
            'data_pagamento' => 'required|date',
            'fornecedor_id' => 'required|exists:fornecedores,id',
        ]);

        Despesa::create($request->only(['descricao', 'valor', 'data_pagamento', 'fornecedor_id']));

        return redirect()
            ->route('contratantes.show', session('contratante_id'))
            ->with('success', 'Despesa cadastrada com sucesso!');
    }

    public function edit($id)
    {
        $despesa = Despesa::findOrFail($id);
        $fornecedores = Fornecedor::all();
        return view('despesas.edit', compact('despesa', 'fornecedores'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'descricao' => 'required|string',
            'valor' => 'required|numeric|min:0',
            'data_pagamento' => 'required|date',
            'fornecedor_id' => 'required|exists:fornecedores,id',
        ]);

        $despesa = Despesa::findOrFail($id);
        $despesa->update($request->only(['descricao', 'valor', 'data_pagamento', 'fornecedor_id']));

        return redirect()
            ->route('contratantes.show', session('contratante_id'))
            ->with('success', 'Despesa atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $despesa = Despesa::findOrFail($id);
        $despesa->delete();

        return redirect()
            ->route('contratantes.show', session('contratante_id'))
            ->with('success', 'Despesa excluída com sucesso!');
    }
}
