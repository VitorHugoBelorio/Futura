@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lançar Nova Despesa</h1>

    <form action="{{ route('despesas.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <input type="text" name="descricao" id="descricao" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="valor" class="form-label">Valor</label>
            <input type="number" step="0.01" name="valor" id="valor" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="data_pagamento" class="form-label">Data de Pagamento</label>
            <input type="date" name="data_pagamento" id="data_pagamento" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="fornecedor_id" class="form-label">Fornecedor</label>
            <select name="fornecedor_id" id="fornecedor_id" class="form-control" required>
                <option value="">Selecione um fornecedor</option>
                @foreach($fornecedores as $fornecedor)
                    <option value="{{ $fornecedor->id }}">{{ $fornecedor->nome }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('contratantes.show', session('contratante_id')) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
