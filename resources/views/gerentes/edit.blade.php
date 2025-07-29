@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Funcionário</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('gerentes.update', $gerente->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" name="nome" id="nome" class="form-control" value="{{ old('nome', $gerente->nome) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $gerente->email) }}" required>
        </div>

        <div class="mb-3">
            <label for="senha" class="form-label">Nova Senha <small>(deixe em branco se não quiser alterar)</small></label>
            <input type="password" name="senha" id="senha" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="{{ route('gerentes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
