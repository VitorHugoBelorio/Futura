<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Futura')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap CSS (opcional) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    @unless (Route::is('login') || Route::is('password.request') || Route::is('password.reset'))
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <img src="{{ asset('images/logo_futura.png') }}" alt="Logo Futura" width="40" height="40" class="d-inline-block align-text-top">
                    Futura
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        @auth
                            <li class="nav-item">
                                <span class="nav-link">Olá, {{ auth()->user()->nome }}</span>
                            </li>
                            <li class="nav-item">
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-link nav-link" type="submit">Sair</button>
                                </form>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
    @endunless

    <div class="container mt-4">
        @yield('content')
    </div>

</body>
</html>
