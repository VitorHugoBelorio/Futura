@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dashboard do Contratante</h1>

    <a href="{{ route('contratante.relatorio.pdf', request()->all()) }}" class="btn btn-outline-dark mb-3">
        Baixar Relatório PDF
    </a>


    <form method="GET" action="{{ route('contratante.dashboard') }}" id="filtro-form">
        <div class="row">
            <div class="col-md-3">
                <label for="periodo">Período:</label>
                <select name="periodo" id="periodo" class="form-control">
                    <option value="mensal" {{ request('periodo') === 'mensal' ? 'selected' : '' }}>Mensal</option>
                    <option value="semestral" {{ request('periodo') === 'semestral' ? 'selected' : '' }}>Semestral</option>
                    <option value="anual" {{ request('periodo') === 'anual' ? 'selected' : '' }}>Anual</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="data_inicio">Data Início:</label>
                <input type="date" name="data_inicio" id="data_inicio" class="form-control" value="{{ $dataInicio }}">
            </div>

            <div class="col-md-3">
                <label for="data_fim">Data Fim:</label>
                <input type="date" name="data_fim" id="data_fim" class="form-control" value="{{ $dataFim }}">
            </div>

            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary w-50">Filtrar</button>
                <a href="{{ route('contratante.dashboard') }}" class="btn btn-secondary w-50">Limpar</a>
            </div>
        </div>
    </form>
    <br/>

    <div class="row text-center mb-4">
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body">
                    <h5 class="card-title">Receitas</h5>
                    <p class="card-text text-success">R$ {{ number_format($totalReceitas, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-danger">
                <div class="card-body">
                    <h5 class="card-title">Despesas</h5>
                    <p class="card-text text-danger">R$ {{ number_format($totalDespesas, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-info">
                <div class="card-body">
                    <h5 class="card-title">Saldo</h5>
                    <p class="card-text {{ $saldo >= 0 ? 'text-success' : 'text-danger' }}">
                        R$ {{ number_format($saldo, 2, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <h3>Movimentações</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Data</th>
                <th>Tipo</th>
                <th>Descrição</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movimentacoes as $mov)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($mov['data'])->format('d/m/Y') }}</td>
                    <td>{{ $mov['tipo'] }}</td>
                    <td>{{ $mov['descricao'] }}</td>
                    <td class="{{ $mov['tipo'] === 'Receita' ? 'text-success' : 'text-danger' }}">
                        R$ {{ number_format($mov['valor'], 2, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title">Evolução mensal</h5>
        <canvas id="graficoEvolucao" height="100"></canvas>
    </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('graficoEvolucao').getContext('2d');

    const graficoEvolucao = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($graficoDados['labels']),
            datasets: [
                {
                    label: 'Receitas',
                    data: @json($graficoDados['receitas']),
                    borderColor: 'green',
                    backgroundColor: 'rgba(0, 128, 0, 0.1)',
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Despesas',
                    data: @json($graficoDados['despesas']),
                    borderColor: 'red',
                    backgroundColor: 'rgba(255, 0, 0, 0.1)',
                    fill: true,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    type: 'category',
                    ticks: {
                        autoSkip: false
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR');
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) label += ': ';
                            label += 'R$ ' + context.raw.toLocaleString('pt-BR');
                            return label;
                        }
                    }
                }
            }
        }
    });
</script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const periodoSelect = document.querySelector('select[name="periodo"]');
            const dataInicioInput = document.querySelector('input[name="data_inicio"]');
            const dataFimInput = document.querySelector('input[name="data_fim"]');

            periodoSelect.addEventListener('change', function () {
                const now = new Date();
                let inicio, fim;

                if (this.value === 'mensal') {
                    // Primeiro e último dia do mês atual
                    inicio = new Date(now.getFullYear(), now.getMonth(), 1);
                    fim = new Date(now.getFullYear(), now.getMonth() + 1, 0);

                } else if (this.value === 'semestral') {
                    const mes = now.getMonth(); // 0 a 11
                    const ano = now.getFullYear();

                    if (mes < 6) {
                        // Primeiro semestre
                        inicio = new Date(ano, 0, 1);
                        fim = new Date(ano, 5, 30);
                    } else {
                        // Segundo semestre
                        inicio = new Date(ano, 6, 1);
                        fim = new Date(ano, 11, 31);
                    }

                } else if (this.value === 'anual') {
                    const ano = now.getFullYear();
                    inicio = new Date(ano, 0, 1);
                    fim = new Date(ano, 11, 31);
                }

                // Preenche os campos de data no formato YYYY-MM-DD
                const formatDate = (date) => date.toISOString().split('T')[0];

                dataInicioInput.value = formatDate(inicio);
                dataFimInput.value = formatDate(fim);
            });
        });
    </script>

@endsection
