@extends('layouts.app')

@section('title', 'Recuperar Senha - Futura')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <h5 class="text-center mb-4">Recuperar Senha</h5>

                @if (session('status'))
                    <div class="alert alert-success small">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger small">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Digite seu e-mail</label>
                        <input type="email" name="email" id="email" class="form-control rounded-3" required autofocus>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-3">Enviar link de recuperação</button>
                </form>

                <div class="mt-3 text-center">
                    <a href="{{ route('login') }}" class="small text-decoration-none">Voltar para o login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
