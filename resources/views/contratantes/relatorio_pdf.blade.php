<!DOCTYPE html>
    <html>
    <head>
    <meta charset="utf-8">
    <title>Relatório Financeiro</title>
    <style>
        body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
        margin: 40px;
        color: #333;
        position: relative;
        }

        h2, h4 {
            text-align: center;
            margin: 0;
            font-weight: normal;
        }

        h2 {
            font-size: 20px;
            margin-bottom: 4px;
        }

        h4 {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        .divider {
            border-top: 1px solid #aaa;
            margin: 10px 0 20px;
        }

        p {
            margin: 4px 0;
        }

        .summary {
            margin-bottom: 20px;
        }

        .text-success { color: #2e7d32; }
        .text-danger { color: #c62828; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 8px 6px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background-color: #f9f9f9;
            font-weight: bold;
            font-size: 12px;
        }

        .valor {
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #888;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <h2>Relatório Financeiro</h2>
    <h4>{{ $nomeContratante }}</h4>

    <div class="divider"></div>

    <div class="summary">
        <p><strong>Período:</strong> {{ $dataInicio->format('d/m/Y') }} a {{ $dataFim->format('d/m/Y') }}</p>
        <p><strong>Total de Receitas:</strong> <span class="valor text-success">R$ {{ number_format($totalReceitas, 2, ',', '.') }}</span></p>
        <p><strong>Total de Despesas:</strong> <span class="valor text-danger">R$ {{ number_format($totalDespesas, 2, ',', '.') }}</span></p>
        <p><strong>Saldo:</strong>
            <span class="valor {{ $saldo >= 0 ? 'text-success' : 'text-danger' }}">
                R$ {{ number_format($saldo, 2, ',', '.') }}
            </span>
        </p>
    </div>

    <h3 style="margin-top: 30px; font-size: 14px;">Movimentações</h3>
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

    <div class="footer">
        Relatório gerado por Sistema Futura &mdash; {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
