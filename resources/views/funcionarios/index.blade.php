{{-- filepath: resources/views/funcionarios/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Funcionários</h1>

    <div class="mb-3 d-flex justify-content-between">
        <a href="{{ route('gerentes.dashboard') }}" class="btn btn-secondary">Voltar</a>
        <a href="{{ route('funcionarios.create') }}" class="btn btn-primary">Novo Funcionário</a>
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
