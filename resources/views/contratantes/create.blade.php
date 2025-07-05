@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Novo Contratante</h1>

    <form action="{{ route('contratantes.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" name="nome" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="cnpj" class="form-label">CNPJ:</label>
            <input type="text" name="cnpj" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-mail:</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="telefone" class="form-label">Telefone:</label>
            <input type="text" name="telefone" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Cadastrar</button>
        <a href="{{ route('contratantes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
