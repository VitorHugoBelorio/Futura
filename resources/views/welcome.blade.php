<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Bem-vindo à Futura</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/logo_futura.png') }}">
    <link rel="stylesheet" href="{{ asset('css/welcome_style.css') }}">
</head>
<body>
    <div class="container">
        <img src="{{ asset('images/logoFuturaCompleta.jpg') }}" alt="Logo Futura" class="logo">
        <h1>Bem-vindo ao sistema Futura</h1>
        <p>Simplificando sua contabilidade com eficiência e inovação.</p>
        <a href="{{ route('login') }}" class="btn">Acessar o Sistema</a>
    </div>
</body>
</html>
