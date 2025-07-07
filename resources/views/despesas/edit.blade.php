@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Despesa</h1>

    <form action="{{ route('despesas.update', $despesa->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <input type="text" name="descricao" id="descricao" class="form-control" value="{{ $despesa->descricao }}" required>
        </div>

        <div class="mb-3">
            <label for="valor" class="form-label">Valor</label>
            <input type="number" step="0.01" name="valor" id="valor" class="form-control" value="{{ $despesa->valor }}" required>
        </div>

        <div class="mb-3">
            <label for="data_pagamento" class="form-label">Data de Pagamento</label>
            <input type="date" name="data_pagamento" id="data_pagamento" class="form-control" value="{{ $despesa->data_pagamento }}" required>
        </div>

        <div class="mb-3">
            <label for="fornecedor_id" class="form-label">Fornecedor</label>
            <select name="fornecedor_id" id="fornecedor_id" class="form-control" required>
                @foreach($fornecedores as $fornecedor)
                    <option value="{{ $fornecedor->id }}" {{ $fornecedor->id == $despesa->fornecedor_id ? 'selected' : '' }}>
                        {{ $fornecedor->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="{{ route('contratantes.show', session('contratante_id')) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
