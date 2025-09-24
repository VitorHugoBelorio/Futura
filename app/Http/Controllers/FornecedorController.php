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
            return back()->with('error', 'Não foi possível carregar o formulário de fornecedor. Tente novamente.');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->merge([
                'nome' => trim($request->nome),
                'cnpj' => preg_replace('/\D/', '', $request->cnpj),
                'telefone' => $request->telefone ? preg_replace('/\D/', '', $request->telefone) : null,
            ]);

            $request->validate([
                'nome' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[A-Za-zÀ-ú\s]+$/u',
                ],
                'cnpj' => [
                    'required',
                    'string',
                    'max:18',
                    'unique:tenant_temp.fornecedores,cnpj',
                    function ($attribute, $value, $fail) {
                        if (!preg_match('/^\d{14}$/', $value)) {
                            $fail('O CNPJ informado não é válido.');
                        }
                    }
                ],
                'telefone' => [
                    'nullable',
                    'string',
                    'max:20',
                    'regex:/^\(?\d{2}\)?[\s-]?\d{4,5}-?\d{4}$/',
                ],
            ], [
                'nome.required' => 'O nome é obrigatório.',
                'nome.regex' => 'O nome deve conter apenas letras e espaços.',
                'cnpj.required' => 'O CNPJ é obrigatório.',
                'cnpj.unique' => 'Este CNPJ já está cadastrado no sistema.',
                'telefone.regex' => 'Digite um número de telefone válido.',
            ]);

            Fornecedor::create([
                'nome' => ucwords(mb_strtolower($request->nome, 'UTF-8')),
                'cnpj' => $request->cnpj,
                'telefone' => $request->telefone,
            ]);

            return redirect()
                ->route('contratantes.show', session('contratante_id'))
                ->with('success', 'Fornecedor cadastrado com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Não foi possível cadastrar o fornecedor. Verifique os dados e tente novamente.');
        }
    }

    public function edit($id)
    {
        try {
            $fornecedor = Fornecedor::findOrFail($id);
            return view('fornecedores.edit', compact('fornecedor'));
        } catch (\Exception $e) {
            return back()->with('error', 'Não foi possível carregar os dados do fornecedor.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $fornecedor = Fornecedor::findOrFail($id);

            $request->merge([
                'nome' => trim($request->nome),
                'cnpj' => preg_replace('/\D/', '', $request->cnpj),
                'telefone' => $request->telefone ? preg_replace('/\D/', '', $request->telefone) : null,
            ]);

            $request->validate([
                'nome' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[A-Za-zÀ-ú\s]+$/u',
                ],
                'cnpj' => [
                    'required',
                    'string',
                    'max:18',
                    'unique:tenant_temp.fornecedores,cnpj,' . $fornecedor->id,
                    function ($attribute, $value, $fail) {
                        if (!preg_match('/^\d{14}$/', $value)) {
                            $fail('O CNPJ informado não é válido.');
                        }
                    }
                ],
                'telefone' => [
                    'nullable',
                    'string',
                    'max:20',
                    'regex:/^\(?\d{2}\)?[\s-]?\d{4,5}-?\d{4}$/',
                ],
            ], [
                'nome.required' => 'O nome é obrigatório.',
                'nome.regex' => 'O nome deve conter apenas letras e espaços.',
                'cnpj.required' => 'O CNPJ é obrigatório.',
                'cnpj.unique' => 'Este CNPJ já está cadastrado no sistema.',
                'telefone.regex' => 'Digite um número de telefone válido.',
            ]);

            $fornecedor->update([
                'nome' => ucwords(mb_strtolower($request->nome, 'UTF-8')),
                'cnpj' => $request->cnpj,
                'telefone' => $request->telefone,
            ]);

            return redirect()
                ->route('contratantes.show', session('contratante_id'))
                ->with('success', 'Fornecedor atualizado com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Não foi possível atualizar o fornecedor. Tente novamente.');
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
            return back()->with('error', 'Não foi possível excluir o fornecedor. Tente novamente.');
        }
    }
}
