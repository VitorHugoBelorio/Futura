<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Despesa;
use App\Models\Fornecedor;

class DespesaController extends Controller
{
    private function setTenantConnection()
    {
        try {
            $contratanteId = session('contratante_id');
            $contratante = \App\Models\Contratante::find($contratanteId);

            if (!$contratante) {
                abort(403, 'Contratante não encontrado na sessão.');
            }

            config(['database.connections.tenant_temp.database' => $contratante->banco_dados]);
        } catch (\Exception $e) {
            abort(500, 'Erro ao configurar conexão do contratante: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $this->setTenantConnection();

            $fornecedores = (new Fornecedor())
                ->setConnection('tenant_temp')
                ->newQuery()
                ->get();

            return view('despesas.create', compact('fornecedores'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar formulário de despesa: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $this->setTenantConnection();

            $request->validate([
                'descricao' => 'required|string',
                'valor' => 'required|numeric|min:0',
                'data_pagamento' => 'required|date',
                'fornecedor_id' => 'required',
            ]);

            $fornecedor = (new Fornecedor())->setConnection('tenant_temp')->find($request->fornecedor_id);

            if (!$fornecedor) {
                return back()->withErrors(['fornecedor_id' => 'Fornecedor não encontrado no banco do contratante.']);
            }

            (new Despesa())->setConnection('tenant_temp')->create($request->only([
                'descricao', 'valor', 'data_pagamento', 'fornecedor_id'
            ]));

            return redirect()
                ->route('contratantes.show', session('contratante_id'))
                ->with('success', 'Despesa cadastrada com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao cadastrar despesa: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $this->setTenantConnection();

            $despesa = (new Despesa())->setConnection('tenant_temp')->findOrFail($id);
            $fornecedores = (new Fornecedor())->setConnection('tenant_temp')->newQuery()->get();

            return view('despesas.edit', compact('despesa', 'fornecedores'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar despesa: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->setTenantConnection();

            $request->validate([
                'descricao' => 'required|string',
                'valor' => 'required|numeric|min:0',
                'data_pagamento' => 'required|date',
                'fornecedor_id' => 'required',
            ]);

            $despesa = (new Despesa())->setConnection('tenant_temp')->findOrFail($id);
            $despesa->update($request->only(['descricao', 'valor', 'data_pagamento', 'fornecedor_id']));

            return redirect()
                ->route('contratantes.show', session('contratante_id'))
                ->with('success', 'Despesa atualizada com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar despesa: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->setTenantConnection();

            $despesa = (new Despesa())->setConnection('tenant_temp')->findOrFail($id);
            $despesa->delete();

            return redirect()
                ->route('contratantes.show', session('contratante_id'))
                ->with('success', 'Despesa excluída com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir despesa: ' . $e->getMessage());
        }
    }
}
