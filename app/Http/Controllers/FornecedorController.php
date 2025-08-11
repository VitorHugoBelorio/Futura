<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fornecedor;

class FornecedorController extends Controller
{
    public function create()
    {
        return view('fornecedores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string',
            'cnpj' => 'required|string|max:18|unique:tenant_temp.fornecedores,cnpj',
            'telefone' => 'nullable|string',
        ]);

        Fornecedor::create([
            'nome' => ucfirst(strtolower(trim($request->nome))),
            'cnpj' => preg_replace('/\D/', '', $request->cnpj), // mantém só números
            'telefone' => $request->telefone ? preg_replace('/\D/', '', $request->telefone) : null,
        ]);

        return redirect()
            ->route('contratantes.show', session('contratante_id'))
            ->with('success', 'Fornecedor cadastrado com sucesso!');
    }

    public function edit($id)
    {
        $fornecedor = Fornecedor::findOrFail($id);
        return view('fornecedores.edit', compact('fornecedor'));
    }

    public function update(Request $request, $id)
    {
        $fornecedor = Fornecedor::findOrFail($id);

        $request->validate([
            'nome' => 'required|string',
            'cnpj' => 'required|string|max:18|unique:tenant_temp.fornecedores,cnpj,' . $fornecedor->id,
            'telefone' => 'nullable|string',
        ]);

        $fornecedor->update([
            'nome' => ucfirst(strtolower(trim($request->nome))),
            'cnpj' => preg_replace('/\D/', '', $request->cnpj),
            'telefone' => $request->telefone ? preg_replace('/\D/', '', $request->telefone) : null,
        ]);

        return redirect()
            ->route('contratantes.show', session('contratante_id'))
            ->with('success', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $fornecedor = Fornecedor::findOrFail($id);
        $fornecedor->delete();

        return redirect()
            ->route('contratantes.show', session('contratante_id'))
            ->with('success', 'Fornecedor excluído com sucesso!');
    }
}
