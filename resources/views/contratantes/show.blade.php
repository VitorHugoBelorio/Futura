@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalhes do Contratante</h1>

    <ul class="list-group mb-3">
        <li class="list-group-item"><strong>Nome:</strong> {{ $contratante->nome }}</li>
        <li class="list-group-item"><strong>CNPJ:</strong> {{ $contratante->cnpj }}</li>
        <li class="list-group-item"><strong>E-mail:</strong> {{ $contratante->email }}</li>
        <li class="list-group-item"><strong>Telefone:</strong> {{ $contratante->telefone }}</li>
        <li class="list-group-item"><strong>Banco:</strong> {{ $contratante->banco_dados }}</li>
    </ul>

    {{-- Botões de ação --}}
    <div class="mb-4 d-flex gap-2">
        <a href="{{ route('fornecedores.create') }}" class="btn btn-primary">Novo Fornecedor</a>
        <a href="{{ route('receitas.create') }}" class="btn btn-success">Lançar Receita</a>
        <a href="{{ route('despesas.create') }}" class="btn btn-danger">Lançar Despesa</a>
    </div>

    {{-- Fornecedores --}}
    <h3>Fornecedores</h3>
    @if($fornecedores->count())
        <ul class="list-group mb-3">
            @foreach($fornecedores as $fornecedor)
                <li class="list-group-item">
                    {{ $fornecedor->nome }} - {{ $fornecedor->documento }}
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
                <li class="list-group-item" style="background-color: #f8f9fa;">
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

    <a href="{{ route('contratantes.index') }}" class="btn btn-secondary">Voltar</a>
</div>
@endsection
