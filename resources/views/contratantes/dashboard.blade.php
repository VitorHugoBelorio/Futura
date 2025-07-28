@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dashboard do Contratante</h1>

    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-3">
            <label>Mês</label>
            <select name="mes" class="form-control">
                @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" {{ $m == $mes ? 'selected' : '' }}>
                        {{ str_pad($m, 2, '0', STR_PAD_LEFT) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label>Ano</label>
            <input type="number" name="ano" value="{{ $ano }}" class="form-control">
        </div>
        <div class="col-md-3 align-self-end">
            <button class="btn btn-primary">Filtrar</button>
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
</div>
@endsection
