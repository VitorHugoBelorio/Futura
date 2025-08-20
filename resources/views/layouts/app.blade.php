<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>@yield('title', 'Futura')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/logo_futura.png') }}">
        {{-- Bootstrap CSS (opcional) --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <!-- Bootstrap JS (v5) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
        <style>
        /* Loader */
        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loader-overlay.d-none {
            display: none !important;
        }

        .spinner {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #004080; /* azul marinho */
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

</head>
    <body>
        <!-- Loader -->
        <div id="loader" class="loader-overlay d-none">
            <div class="spinner"></div>
        </div>


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

                    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                        <ul class="navbar-nav">
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


        <script>
            function showLoader() {
                document.getElementById('loader').classList.remove('d-none');
            }

            function hideLoader() {
                document.getElementById('loader').classList.add('d-none');
            }

            // Mostra loader ao enviar formulários
            document.addEventListener("DOMContentLoaded", function() {
                let forms = document.querySelectorAll("form");
                forms.forEach(form => {
                    form.addEventListener("submit", function() {
                        showLoader();
                    });
                });
            });

            // Mostra loader em links com classe "with-loader"
            document.addEventListener("click", function(e) {
                if(e.target.classList.contains("with-loader")) {
                    showLoader();
                }
            });
        </script>
    </body>
</html>
