@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Selecionar Contratante</h1>

    <form action="{{ route('selecionar.contratante.definir') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="contratante_id" class="form-label">Contratante:</label>
            <select name="contratante_id" class="form-select" required>
                <option value="">-- Selecione --</option>
                @foreach($contratantes as $contratante)
                    <option value="{{ $contratante->id }}">{{ $contratante->nome }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Continuar</button>
    </form>
</div>
@endsection
