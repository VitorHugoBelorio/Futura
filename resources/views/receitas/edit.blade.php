@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Receita</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Erros encontrados:</strong>
            <ul>
                @foreach($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('receitas.update', $receita) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <input type="text" name="descricao" id="descricao" class="form-control" required value="{{ old('descricao', $receita->descricao) }}">
        </div>

        <div class="mb-3">
            <label for="valor" class="form-label">Valor</label>
            <input type="number" name="valor" id="valor" class="form-control" required step="0.01" value="{{ old('valor', $receita->valor) }}">
        </div>

        <div class="mb-3">
            <label for="data" class="form-label">Data</label>
            <input type="date" name="data" id="data" class="form-control" required value="{{ old('data', $receita->data) }}">
        </div>

        <div class="mb-3">
            <label for="data_recebimento" class="form-label">Data de Recebimento</label>
            <input type="date" name="data_recebimento" id="data_recebimento" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar Receita</button>
        <a href="{{ route('receitas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
