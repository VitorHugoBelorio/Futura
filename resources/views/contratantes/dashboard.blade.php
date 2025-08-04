@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dashboard do Contratante</h1>

    <a href="{{ route('contratante.relatorio.pdf', request()->all()) }}" class="btn btn-outline-dark mb-3">
        Baixar Relatório PDF
    </a>


    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-3">
            <label>Data Início</label>
            <input type="date" name="data_inicio" value="{{ request('data_inicio', $dataInicio) }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label>Data Fim</label>
            <input type="date" name="data_fim" value="{{ request('data_fim', $dataFim) }}" class="form-control">
        </div>
        <div class="col-md-3 align-self-end d-flex gap-2">
            <button class="btn btn-primary w-100">Filtrar</button>
            <a href="{{ route('contratante.dashboard') }}" class="btn btn-secondary w-100">Resetar</a>
        </div>
    </form>


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
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => 'R$ ' + value.toLocaleString('pt-BR')
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: (context) => {
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

@endsection
