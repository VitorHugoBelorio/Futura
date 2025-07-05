@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalhes do Contratante</h1>

    <ul class="list-group mb-3">
        <li class="list-group-item"><strong>Nome:</strong> {{ $contratante->nome }}</li>
        <li class="list-group-item"><strong>CNPJ:</strong> {{ $contratante->cnpj }}</li>
        <li class="list-group-item"><strong>E-mail:</strong> {{ $contratante->email }}</li>
        <li class="list-group-item"><strong>Telefone:</strong> {{ $contratante->telefone }}</li>
        <li class="list-group-item"><strong>Banco:</strong> {{ $contratante->banco_dados }}</li>
    </ul>

    <a href="{{ route('contratantes.index') }}" class="btn btn-secondary">Voltar</a>
</div>
@endsection
