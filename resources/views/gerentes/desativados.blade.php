{{-- Crie a view gerentes/desativados.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Gerentes Desativados</h2>
        <a href="{{ route('gerentes.index') }}" class="btn btn-outline-secondary fw-semibold">Voltar</a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table">
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
                        <form action="{{ route('gerentes.reativar', $gerente->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success">Reativar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection