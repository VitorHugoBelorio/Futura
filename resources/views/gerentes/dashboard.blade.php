@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Bem-vindo ao Dashboard do Gerente!</h1>
</div>


<div class="container">
    <h1>Contratantes</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('contratantes.create') }}" class="btn btn-primary mb-3">Novo Contratante</a>
    <a href="{{ route('funcionarios.index') }}" class="btn btn-secondary mb-3">Gerenciar Funcionários</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>CNPJ</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contratantes as $contratante)
                <tr>
                    <td>{{ $contratante->nome }}</td>
                    <td>{{ $contratante->cnpj }}</td>
                    <td>{{ $contratante->email }}</td>
                    <td>{{ $contratante->telefone }}</td>
                    <td>
                        <a href="{{ route('contratantes.show', $contratante) }}" class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('contratantes.edit', $contratante) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('contratantes.destroy', $contratante) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Tem certeza que deseja excluir?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Excluir</button> <!--CORRIGIR: Ao excluir um contratante, excluir o seu banco de dados também-->
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
