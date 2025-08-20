{{-- filepath: resources/views/funcionarios/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Funcionários</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif

    <div class="d-flex justify-content-end align-items-center gap-2 mb-4">
        <a href="{{ route('gerentes.dashboard') }}" class="btn btn-outline-secondary fw-semibold">Voltar</a>
        <a href="{{ route('funcionarios.create') }}" class="btn btn-primary fw-semibold">Novo Funcionário</a>
        <a href="{{ route('funcionarios.desativados') }}" class="btn btn-warning fw-semibold">Funcionários Desativados</a>
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
            @foreach($funcionarios as $funcionario)
                <tr>
                    <td>{{ $funcionario->nome }}</td>
                    <td>{{ $funcionario->email }}</td>
                    <td>
                        <a href="{{ route('funcionarios.edit', $funcionario) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('funcionarios.destroy', $funcionario) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir?')">
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
