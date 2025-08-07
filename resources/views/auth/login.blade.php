@extends('layouts.app')

@section('title', 'Login - Futura')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <img src="{{ asset('images/logo_futura.png') }}" alt="Logo Futura" class="mb-3" style="max-width: 130px;">
                    <h3 class="fw-semibold mb-1">Bem-vindo de volta</h3>
                    <p class="text-muted small mb-0">Acesse o sistema Futura com seu e-mail e senha</p>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.attempt') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" id="email" name="email" class="form-control rounded-end" value="{{ old('email') }}" required autofocus>
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" id="password" name="password" class="form-control rounded-end" required>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div></div>
                        <a href="{{ route('password.request') }}" class="small text-decoration-none">Esqueci minha senha</a>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-3">Entrar</button>

                    @if ($errors->has('error'))
                        <div class="alert alert-danger small mt-3">{{ $errors->first('error') }}</div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
