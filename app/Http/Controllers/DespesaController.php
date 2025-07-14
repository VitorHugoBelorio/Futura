<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Despesa;
use App\Models\Fornecedor;

class DespesaController extends Controller
{
    private function setTenantConnection()
    {
        $contratanteId = session('contratante_id');
        $contratante = \App\Models\Contratante::find($contratanteId);

        if (!$contratante) {
            abort(403, 'Contratante não encontrado na sessão.');
        }

        config(['database.connections.tenant_temp.database' => $contratante->banco_dados]);
    }

    public function create()
    {
        $this->setTenantConnection();

        $fornecedores = (new Fornecedor())
            ->setConnection('tenant_temp')
            ->newQuery()
            ->get();

        return view('despesas.create', compact('fornecedores'));
    }

    public function store(Request $request)
    {
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
    }

    public function edit($id)
    {
        $this->setTenantConnection();

        $despesa = (new Despesa())->setConnection('tenant_temp')->findOrFail($id);
        $fornecedores = (new Fornecedor())->setConnection('tenant_temp')->newQuery()->get();

        return view('despesas.edit', compact('despesa', 'fornecedores'));
    }

    public function update(Request $request, $id)
    {
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
    }

    public function destroy($id)
    {
        $this->setTenantConnection();

        $despesa = (new Despesa())->setConnection('tenant_temp')->findOrFail($id);
        $despesa->delete();

        return redirect()
            ->route('contratantes.show', session('contratante_id'))
            ->with('success', 'Despesa excluída com sucesso!');
    }
}
