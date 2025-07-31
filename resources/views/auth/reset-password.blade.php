@extends('layouts.app')

@section('title', 'Redefinir Senha - Futura')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <h5 class="text-center mb-4">Redefinir Senha</h5>

                @if (session('error'))
                    <div class="alert alert-danger small">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="mb-3">
                        <label for="password" class="form-label">Nova Senha</label>
                        <input type="password" name="password" id="password" class="form-control rounded-3" required>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control rounded-3" required>
                        @error('password_confirmation')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-3">Salvar nova senha</button>
                </form>

                <div class="mt-3 text-center">
                    <a href="{{ route('login') }}" class="small text-decoration-none">Voltar para o login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
