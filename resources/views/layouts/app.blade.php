<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Sistema Futura</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap 5 CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Opcional: Ícone e estilo personalizado --}}
    <style>
        body {
            padding-top: 60px;
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: bold;
        }
    </style>
</head>
<body>

    {{-- Navbar fixa no topo --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('contratantes.index') }}">Futura</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    {{-- Você pode adicionar autenticação aqui futuramente --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contratantes.index') }}">Contratantes</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Conteúdo principal --}}
    <main class="container">
        @yield('content')
    </main>

    {{-- Bootstrap JS (opcional para interações) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
