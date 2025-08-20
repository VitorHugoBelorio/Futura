@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Contratantes Desativados</h2>
        <a href="{{ route('gerentes.dashboard') }}" class="btn btn-outline-secondary fw-semibold">Voltar</a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>CNPJ</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contratantes as $contratante)
                <tr>
                    <td>{{ $contratante->nome }}</td>
                    <td>{{ $contratante->email }}</td>
                    <td>{{ $contratante->cnpj }}</td>
                    <td>
                        <form action="{{ route('contratantes.reativar', $contratante->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success">Reativar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection