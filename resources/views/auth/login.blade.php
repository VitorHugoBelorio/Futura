@extends('layouts.app')

@section('title', 'Login - Futura')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <h5 class="text-center mb-4">Acesso ao Sistema</h5>

                @if (session('error'))
                    <div class="alert alert-danger small">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.attempt') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" id="email" name="email" class="form-control rounded-3" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" id="password" name="password" class="form-control rounded-3" required>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end mb-3">
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
