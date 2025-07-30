<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório Financeiro</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-success { color: green; }
        .text-danger { color: red; }
        .logo-container { text-align: center; margin-bottom: 10px; }
        .logo-container img { width: 140px; }
        h2, h4 { text-align: center; margin: 0; }
        .divider { border-top: 2px solid #444; margin: 10px 0 20px 0; }
    </style>
</head>
<body>
    <h2>Relatório Financeiro</h2>
    <h4>{{ $nomeContratante }}</h4>
    <div class="divider"></div>

    <p><strong>Período:</strong> {{ $dataInicio->format('d/m/Y') }} até {{ $dataFim->format('d/m/Y') }}</p>

    <p><strong>Total de Receitas:</strong> R$ {{ number_format($totalReceitas, 2, ',', '.') }}</p>
    <p><strong>Total de Despesas:</strong> R$ {{ number_format($totalDespesas, 2, ',', '.') }}</p>
    <p><strong>Saldo:</strong> 
        <span class="{{ $saldo >= 0 ? 'text-success' : 'text-danger' }}">
            R$ {{ number_format($saldo, 2, ',', '.') }}
        </span>
    </p>

    <h3>Movimentações</h3>
    <table>
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
</body>
</html>
