@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalhes do Contratante</h1>

    {{-- Situação Financeira --}}
    <div class="alert alert-info d-flex justify-content-between align-items-center">
        <div>
            <strong>Situação atual:</strong> R$
            {{ number_format($saldo, 2, ',', '.') }}
        </div>
        <form method="GET" class="d-flex align-items-center gap-2">
            <div>
                <label for="data_inicio" class="form-label mb-0 small">De:</label>
                <input type="date" name="data_inicio" id="data_inicio" class="form-control form-control-sm"
                    value="{{ $dataInicio }}">
            </div>
            <div>
                <label for="data_fim" class="form-label mb-0 small">Até:</label>
                <input type="date" name="data_fim" id="data_fim" class="form-control form-control-sm"
                    value="{{ $dataFim }}">
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-sm btn-primary">Filtrar</button>
            </div>
        </form>
    </div>

    <ul class="list-group mb-3">
        <li class="list-group-item"><strong>Nome:</strong> {{ $contratante->nome }}</li>
        <li class="list-group-item"><strong>CNPJ:</strong> {{ $contratante->cnpj }}</li>
        <li class="list-group-item"><strong>E-mail:</strong> {{ $contratante->email }}</li>
        <li class="list-group-item"><strong>Telefone:</strong> {{ $contratante->telefone }}</li>
        <li class="list-group-item"><strong>Banco:</strong> {{ $contratante->banco_dados }}</li>
    </ul>

    {{-- Botões de ação --}}
    <div class="mb-4 d-flex gap-2">
        <a href="{{ route('fornecedores.create')}}" class="btn btn-primary">Novo Fornecedor</a>
        <a href="{{ route('receitas.create')}}" class="btn btn-success">Lançar Receita</a>
        <a href="{{ route('despesas.create')}}" class="btn btn-danger">Lançar Despesa</a>
    </div>

    {{-- Fornecedores --}}
    <h3>Fornecedores</h3>
    @if($fornecedores->count())
        <ul class="list-group mb-3">
            @foreach($fornecedores as $fornecedor)
                <li class="list-group-item">
                    {{ $fornecedor->nome }} - {{ $fornecedor->cnpj }}
                </li>
            @endforeach
        </ul>
    @else
        <p>Nenhum fornecedor cadastrado.</p>
    @endif

    {{-- Receitas --}}
    <h3>Receitas</h3>
    @if($receitas->count())
        <ul class="list-group mb-3">
            @foreach($receitas as $receita)
                <li class="list-group-item" style="background-color: #e6f9e6;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            {{ $receita->descricao }} - R$ {{ number_format($receita->valor, 2, ',', '.') }}
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('receitas.edit', $receita->id) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('receitas.destroy', $receita->id) }}" method="POST" onsubmit="return confirm('Deseja realmente excluir esta receita?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                            </form>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <p>Nenhuma receita cadastrada.</p>
    @endif

    {{-- Despesas --}}
    <h3>Despesas</h3>
    @if($despesas->count())
        <ul class="list-group mb-3">
            @foreach($despesas as $despesa)
                <li class="list-group-item" style="background-color: #fdeaea;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            {{ $despesa->descricao }} - R$ {{ number_format($despesa->valor, 2, ',', '.') }}
                            @if($despesa->fornecedor)
                                <small class="text-muted">(Fornecedor: {{ $despesa->fornecedor->nome }})</small>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('despesas.edit', $despesa->id) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('despesas.destroy', $despesa->id) }}" method="POST" onsubmit="return confirm('Deseja realmente excluir esta despesa?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                            </form>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <p>Nenhuma despesa cadastrada.</p>
    @endif

    <a href="{{ auth()->user()->isGerente() ? route('gerentes.dashboard') : route('funcionarios.dashboard') }}" class="btn btn-secondary">Voltar</a>
</div>
@endsection
