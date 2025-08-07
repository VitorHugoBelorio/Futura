<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receita;
use App\Models\Despesa;
use App\Models\Contratante;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardContratanteController extends Controller
{
    public function index(Request $request)
    {
        $tipoComparacao = $request->input('periodo', 'mensal');

        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $dataInicio = $request->input('data_inicio');
            $dataFim = $request->input('data_fim');
        } else {
            switch ($tipoComparacao) {
                case 'anual':
                    $dataInicio = Carbon::now()->subYears(2)->startOfYear()->toDateString();
                    $dataFim = Carbon::now()->endOfYear()->toDateString();
                    break;

                case 'semestral':
                    $dataInicio = Carbon::now()->subMonths(12)->startOfMonth()->toDateString();
                    $dataFim = Carbon::now()->endOfMonth()->toDateString();
                    break;

                case 'mensal':
                default:
                    $dataInicio = Carbon::now()->subMonths(0)->startOfMonth()->toDateString();
                    $dataFim = Carbon::now()->endOfMonth()->toDateString();
                    break;
            }
        }

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

            $receitas = (new Receita())
                ->setConnection('tenant_temp')
                ->whereBetween('data_recebimento', [$dataInicio, $dataFim])
                ->get();

            $despesas = (new Despesa())
                ->setConnection('tenant_temp')
                ->whereBetween('data_pagamento', [$dataInicio, $dataFim])
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

            $graficoDados = [
                'labels' => [],
                'receitas' => [],
                'despesas' => [],
            ];

            $inicio = Carbon::parse($dataInicio);
            $fim = Carbon::parse($dataFim);

            switch ($tipoComparacao) {
                case 'anual':
                    $periodo = $inicio->copy()->startOfYear()->yearsUntil($fim->copy()->endOfYear());
                    foreach ($periodo as $ano) {
                        $anoInicio = $ano->copy()->startOfYear();
                        $anoFim = $ano->copy()->endOfYear();

                        $receita = (new Receita())->setConnection('tenant_temp')
                            ->whereBetween('data_recebimento', [$anoInicio, $anoFim])
                            ->sum('valor');

                        $despesa = (new Despesa())->setConnection('tenant_temp')
                            ->whereBetween('data_pagamento', [$anoInicio, $anoFim])
                            ->sum('valor');

                        $graficoDados['labels'][] = $ano->format('Y');
                        $graficoDados['receitas'][] = $receita;
                        $graficoDados['despesas'][] = $despesa;
                    }
                    break;

                case 'semestral':
                    $periodo = collect();
                    $dataCursor = $inicio->copy()->startOfYear();
                    while ($dataCursor->lessThanOrEqualTo($fim)) {
                        $periodo->push($dataCursor->copy());
                        $dataCursor->addMonths(6);
                    }

                    foreach ($periodo as $semestre) {
                        $semestreFim = $semestre->copy()->addMonths(5)->endOfMonth();

                        $receita = (new Receita())->setConnection('tenant_temp')
                            ->whereBetween('data_recebimento', [$semestre, $semestreFim])
                            ->sum('valor');

                        $despesa = (new Despesa())->setConnection('tenant_temp')
                            ->whereBetween('data_pagamento', [$semestre, $semestreFim])
                            ->sum('valor');

                        $graficoDados['labels'][] = $semestre->format('m/Y') . ' - ' . $semestreFim->format('m/Y');
                        $graficoDados['receitas'][] = $receita;
                        $graficoDados['despesas'][] = $despesa;
                    }
                    break;

                default: // mensal → exibe por dia
                    $periodo = $inicio->copy()->daysUntil($fim->copy());
                    foreach ($periodo as $dia) {
                        $diaInicio = $dia->copy()->startOfDay();
                        $diaFim = $dia->copy()->endOfDay();

                        $receita = (new Receita())->setConnection('tenant_temp')
                            ->whereBetween('data_recebimento', [$diaInicio, $diaFim])
                            ->sum('valor');

                        $despesa = (new Despesa())->setConnection('tenant_temp')
                            ->whereBetween('data_pagamento', [$diaInicio, $diaFim])
                            ->sum('valor');

                        $graficoDados['labels'][] = $dia->format('d/m');
                        $graficoDados['receitas'][] = $receita;
                        $graficoDados['despesas'][] = $despesa;
                    }
                    break;
            }

            return view('contratantes.dashboard', compact(
                'totalReceitas',
                'totalDespesas',
                'saldo',
                'movimentacoes',
                'dataInicio',
                'dataFim',
                'graficoDados',
                'tipoComparacao',
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao conectar com o banco do contratante: ' . $e->getMessage());
        }
    }

    public function gerarRelatorioPdf(Request $request)
    {
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

            $hoje = now();
            $mes = $request->input('mes', $hoje->month);
            $ano = $request->input('ano', $hoje->year);
            $diaInicio = $request->input('dia_inicio', 1);
            $diaFim = $request->input('dia_fim', now()->daysInMonth);

            $dataInicio = Carbon::create($ano, $mes, $diaInicio)->startOfDay();
            $dataFim = Carbon::create($ano, $mes, $diaFim)->endOfDay();

            $receitas = (new Receita())
                ->setConnection('tenant_temp')
                ->whereBetween('data_recebimento', [$dataInicio, $dataFim])
                ->get();

            $despesas = (new Despesa())
                ->setConnection('tenant_temp')
                ->whereBetween('data_pagamento', [$dataInicio, $dataFim])
                ->get();

            $totalReceitas = $receitas->sum('valor');
            $totalDespesas = $despesas->sum('valor');
            $saldo = $totalReceitas - $totalDespesas;

            $movimentacoes = collect();

            foreach ($receitas as $r) {
                $movimentacoes->push([
                    'data' => $r->data_recebimento,
                    'tipo' => 'Receita',
                    'descricao' => $r->descricao,
                    'valor' => $r->valor
                ]);
            }

            foreach ($despesas as $d) {
                $movimentacoes->push([
                    'data' => $d->data_pagamento,
                    'tipo' => 'Despesa',
                    'descricao' => $d->descricao,
                    'valor' => $d->valor
                ]);
            }

            $movimentacoes = $movimentacoes->sortBy('data');

            $pdf = Pdf::loadView('contratantes.relatorio_pdf', [
                'totalReceitas' => $totalReceitas,
                'totalDespesas' => $totalDespesas,
                'saldo' => $saldo,
                'movimentacoes' => $movimentacoes,
                'dataInicio' => $dataInicio,
                'dataFim' => $dataFim,
                'nomeContratante' => $contratante->nome,
            ]);

            return $pdf->download('relatorio_' . $dataInicio->format('m_Y') . '.pdf');

        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao gerar relatório: ' . $e->getMessage());
        }
    }

}
