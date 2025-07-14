@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Novo Fornecedor</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Erro!</strong> Verifique os campos abaixo:
            <ul>
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('fornecedores.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" value="{{ old('nome') }}" required>
        </div>

        <div class="mb-3">
            <label for="cnpj" class="form-label">CNPJ</label>
            <input type="text" name="cnpj" class="form-control" value="{{ old('cnpj') }}" required>
        </div>

        <div class="mb-3">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="text" name="telefone" class="form-control" value="{{ old('telefone') }}">
        </div>

        <button type="submit" class="btn btn-primary">Cadastrar</button>
        <a href="{{ route('contratantes.show', session('contratante_id')) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
