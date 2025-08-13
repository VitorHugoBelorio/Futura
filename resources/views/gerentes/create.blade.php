@extends('layouts.app')

@section('title', 'Novo Gerente')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Cadastrar Novo Gerente</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ops!</strong> Há alguns erros no formulário.<br><br>
            <ul class="mb-0">
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('gerentes.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Gerente</label>
            <input type="text" name="nome" class="form-control" value="{{ old('nome') }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('gerentes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
