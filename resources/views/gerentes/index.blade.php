@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Gerentes</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif

    <div class="d-flex justify-content-end gap-4 mb-4">
        <a href="{{ route('gerentes.dashboard') }}" class="btn btn-outline-secondary px-4 py-2 fw-semibold">Voltar</a>
        <a href="{{ route('gerentes.create') }}" class="btn btn-primary px-4 py-2 fw-semibold">Novo Gerente</a>
        <a href="{{ route('gerentes.desativados') }}" class="btn btn-warning px-4 py-2 fw-semibold">Gerentes Desativados</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gerentes as $gerente)
                <tr>
                    <td>{{ $gerente->nome }}</td>
                    <td>{{ $gerente->email }}</td>
                    <td>
                        <a href="{{ route('gerentes.edit', $gerente) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('gerentes.destroy', $gerente) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
