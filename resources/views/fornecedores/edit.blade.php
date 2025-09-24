@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Fornecedor</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('fornecedores.update', $fornecedor->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" value="{{ old('nome', $fornecedor->nome) }}" required>
        </div>

        <div class="mb-3">
            <label for="cnpj" class="form-label">CNPJ</label>
            <input type="text" name="cnpj" class="form-control" value="{{ old('cnpj', $fornecedor->cnpj) }}" required>
        </div>

        <div class="mb-3">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="text" name="telefone" class="form-control" value="{{ old('telefone', $fornecedor->telefone) }}">
        </div>

        <button type="submit" class="btn btn-warning">Atualizar</button>
        <a href="{{ route('contratantes.show', session('contratante_id')) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
