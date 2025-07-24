@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Bem-vindo ao Dashboard do funcionário!</h1>
</div>

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
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
