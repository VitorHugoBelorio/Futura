<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receita;
use App\Models\Despesa;
use App\Models\Contratante;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardContratanteController extends Controller
{
    public function index(Request $request)
    {
        $mes = $request->input('mes', now()->format('m'));
        $ano = $request->input('ano', now()->format('Y'));
        $diaInicio = $request->input('dia_inicio');
        $diaFim = $request->input('dia_fim');

        $contratanteId = session('contratante_id');
        if (!$contratanteId) {
            return redirect()->route('selecionar-contratante')->with('error', 'Nenhum contratante selecionado.');
        }

        $contratante = Contratante::find($contratanteId);
        if (!$contratante) {
            return redirect()->route('selecionar-contratante')->with('error', 'Contratante não encontrado.');
        }

        config(['database.connections.tenant_temp.database' => $contratante->banco_dados]);

        try {
            DB::connection('tenant_temp')->getPdo();

            // Define datas completas para filtro
            $inicio = Carbon::createFromDate($ano, $mes, $diaInicio ?? 1)->startOfDay();
            $fim = Carbon::createFromDate($ano, $mes, $diaFim ?? now()->endOfMonth()->day)->endOfDay();

            $receitas = (new Receita())
                ->setConnection('tenant_temp')
                ->whereBetween('data_recebimento', [$inicio, $fim])
                ->get();

            $despesas = (new Despesa())
                ->setConnection('tenant_temp')
                ->whereBetween('data_pagamento', [$inicio, $fim])
                ->get();

            $totalReceitas = $receitas->sum('valor');
            $totalDespesas = $despesas->sum('valor');
            $saldo = $totalReceitas - $totalDespesas;

            $movimentacoes = $receitas->map(function ($r) {
                return [
                    'data' => $r->data_recebimento,
                    'tipo' => 'Receita',
                    'descricao' => $r->descricao,
                    'valor' => $r->valor
                ];
            })->merge($despesas->map(function ($d) {
                return [
                    'data' => $d->data_pagamento,
                    'tipo' => 'Despesa',
                    'descricao' => $d->descricao,
                    'valor' => $d->valor
                ];
            }))->sortByDesc('data');

            return view('contratantes.dashboard', compact(
                'totalReceitas',
                'totalDespesas',
                'saldo',
                'movimentacoes',
                'mes',
                'ano',
                'diaInicio',
                'diaFim'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao conectar com o banco do contratante: ' . $e->getMessage());
        }
    }
}
