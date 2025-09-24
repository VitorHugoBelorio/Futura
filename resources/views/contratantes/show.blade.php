@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 fw-bold text-center">Detalhes do Contratante</h1>

    {{-- Situação Financeira --}}
    <div class="p-3 mb-4 bg-light border rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <strong class="text-primary">Situação atual:</strong>
                <span class="fs-5 text-success fw-semibold">R$ {{ number_format($saldo, 2, ',', '.') }}</span>
            </div>
            <form method="GET" class="d-flex flex-wrap align-items-end gap-3">
                <div>
                    <label for="data_inicio" class="form-label mb-1 small">De:</label>
                    <input type="date" name="data_inicio" id="data_inicio" class="form-control form-control-sm" value="{{ $dataInicio }}">
                </div>
                <div>
                    <label for="data_fim" class="form-label mb-1 small">Até:</label>
                    <input type="date" name="data_fim" id="data_fim" class="form-control form-control-sm" value="{{ $dataFim }}">
                </div>
                <div>
                    <button type="submit" class="btn btn-sm btn-outline-primary mt-3">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Informações do contratante --}}
    <ul class="list-group list-group-flush mb-4 border rounded shadow-sm">
        <li class="list-group-item"><strong>Nome:</strong> {{ $contratante->nome }}</li>
        <li class="list-group-item"><strong>CNPJ:</strong> {{ $contratante->cnpj }}</li>
        <li class="list-group-item"><strong>E-mail:</strong> {{ $contratante->email }}</li>
        <li class="list-group-item"><strong>Telefone:</strong> {{ $contratante->telefone }}</li>
        <!--<li class="list-group-item"><strong>Banco:</strong> {{ $contratante->banco_dados }}</li> --> <!-- Retirar na versão final, está presente apenas para fins de testes e comparação.-->
    </ul>

    {{-- Ações --}}
    <div class="mb-5 d-flex flex-wrap gap-2">
        <a href="{{ route('fornecedores.create') }}" class="btn btn-outline-primary">Novo Fornecedor</a>
        <a href="{{ route('receitas.create') }}" class="btn btn-outline-success">Lançar Receita</a>
        <a href="{{ route('despesas.create') }}" class="btn btn-outline-danger">Lançar Despesa</a>
    </div>

    {{-- Fornecedores --}}
    <h3 class="mb-3">Fornecedores</h3>
    @if($fornecedores->count())
        <ul class="list-group list-group-flush mb-4 border rounded shadow-sm">
            @foreach($fornecedores as $fornecedor)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $fornecedor->nome }}</strong><br>
                        <span class="text-muted small">{{ $fornecedor->cnpj }}</span>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('fornecedores.edit', $fornecedor->id) }}" class="btn btn-sm btn-outline-warning">
                            Editar
                        </a>
                        <form action="{{ route('fornecedores.destroy', $fornecedor->id) }}" method="POST"
                            onsubmit="return confirm('Deseja realmente excluir este fornecedor?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-muted fst-italic">Nenhum fornecedor cadastrado.</p>
    @endif


    {{-- Receitas --}}
    <h4 class="mt-4 mb-2 text-success">Receitas</h4>
    @if($receitas->count())
        <ul class="list-group mb-4 shadow-sm">
            @foreach($receitas as $receita)
                <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                    <div>
                        <strong>{{ $receita->descricao }}</strong><br>
                        <small class="text-muted">R$ {{ number_format($receita->valor, 2, ',', '.') }}</small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('receitas.edit', $receita->id) }}" class="btn btn-sm btn-outline-warning">Editar</a>
                        <form action="{{ route('receitas.destroy', $receita->id) }}" method="POST"
                            onsubmit="return confirm('Deseja realmente excluir esta receita?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-muted">Nenhuma receita cadastrada.</p>
    @endif

    {{-- Despesas --}}
    <h4 class="mt-4 mb-2 text-danger">Despesas</h4>
    @if($despesas->count())
        <ul class="list-group mb-4 shadow-sm">
            @foreach($despesas as $despesa)
                <li class="list-group-item d-flex justify-content-between align-items-center bg-white">
                    <div>
                        <strong>{{ $despesa->descricao }}</strong> -
                        <span class="text-muted">R$ {{ number_format($despesa->valor, 2, ',', '.') }}</span>
                        @if($despesa->fornecedor)
                            <br><small class="text-muted">Fornecedor: {{ $despesa->fornecedor->nome }}</small>
                        @endif
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('despesas.edit', $despesa->id) }}" class="btn btn-sm btn-outline-warning">Editar</a>
                        <form action="{{ route('despesas.destroy', $despesa->id) }}" method="POST"
                            onsubmit="return confirm('Deseja realmente excluir esta despesa?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-muted">Nenhuma despesa cadastrada.</p>
    @endif

    {{-- Botão voltar --}}
    <a href="{{ auth()->user()->isGerente() ? route('gerentes.dashboard') : route('funcionarios.dashboard') }}" class="btn btn-outline-secondary">Voltar</a>
</div>
@endsection
