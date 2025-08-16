<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fornecedor;

class FornecedorController extends Controller
{
    public function create()
    {
        try {
            return view('fornecedores.create');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar formulário: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nome' => 'required|string',
                'cnpj' => 'required|string|max:18|unique:tenant_temp.fornecedores,cnpj',
                'telefone' => 'nullable|string',
            ]);

            Fornecedor::create([
                'nome' => ucfirst(strtolower(trim($request->nome))),
                'cnpj' => preg_replace('/\D/', '', $request->cnpj),
                'telefone' => $request->telefone ? preg_replace('/\D/', '', $request->telefone) : null,
            ]);

            return redirect()
                ->route('contratantes.show', session('contratante_id'))
                ->with('success', 'Fornecedor cadastrado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao cadastrar fornecedor: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $fornecedor = Fornecedor::findOrFail($id);
            return view('fornecedores.edit', compact('fornecedor'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar fornecedor: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
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
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar fornecedor: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $fornecedor = Fornecedor::findOrFail($id);
            $fornecedor->delete();

            return redirect()
                ->route('contratantes.show', session('contratante_id'))
                ->with('success', 'Fornecedor excluído com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir fornecedor: ' . $e->getMessage());
        }
    }
}
