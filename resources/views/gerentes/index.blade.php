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

    <div class="mb-3 d-flex justify-content-between">
        <a href="{{ route('gerentes.dashboard') }}" class="btn btn-secondary">Voltar</a>
        <a href="{{ route('gerentes.create') }}" class="btn btn-primary">Novo Gerente</a>
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
