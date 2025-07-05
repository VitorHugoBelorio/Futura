@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Contratante</h1>

    <form action="{{ route('contratantes.update', $contratante) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" name="nome" class="form-control" value="{{ $contratante->nome }}" required>
        </div>

        <div class="mb-3">
            <label for="cnpj" class="form-label">CNPJ:</label>
            <input type="text" name="cnpj" class="form-control" value="{{ $contratante->cnpj }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-mail:</label>
            <input type="email" name="email" class="form-control" value="{{ $contratante->email }}" required>
        </div>

        <div class="mb-3">
            <label for="telefone" class="form-label">Telefone:</label>
            <input type="text" name="telefone" class="form-control" value="{{ $contratante->telefone }}">
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('contratantes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
