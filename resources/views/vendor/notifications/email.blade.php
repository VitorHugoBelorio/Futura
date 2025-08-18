<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Redefinição de Senha - Futura</title>
    <style>
        body {
            font-family: 'Segoe UI', Roboto, Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .card {
            width: 100%;
            max-width: 480px;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.07);
            overflow: hidden;
        }
        .header {
            background: #fff;
            padding: 25px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }
        .header img {
            width: 140px;
        }
        .content {
            padding: 35px 25px;
            text-align: center;
        }
        .content h1 {
            font-size: 22px;
            margin-bottom: 18px;
            color: #004aad;
            font-weight: 600;
        }
        .content p {
            font-size: 15px;
            margin-bottom: 18px;
            line-height: 1.6;
            color: #555;
        }
        .btn {
            display: inline-block;
            padding: 12px 26px;
            background-color: #004aad;
            color: #fff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background-color: #00347a;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #888;
            border-top: 1px solid #eee;
            line-height: 1.5;
        }
        .footer a {
            color: #004aad;
            text-decoration: none;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="card">
        <div>
            <!-- Cabeçalho -->
            <div class="header">
                <img src="{{ asset('images/logoFuturaCompleta.jpg') }}" alt="Futura Logo">
            </div>

            <!-- Conteúdo -->
            <div class="content">
                <h1>Redefinição de Senha</h1>
                
                @foreach ($introLines as $line)
                    <p>{{ $line }}</p>
                @endforeach

                @isset($actionText)
                    <a href="{{ $actionUrl }}" class="btn">{{ $actionText }}</a>
                @endisset

                @foreach ($outroLines as $line)
                    <p>{{ $line }}</p>
                @endforeach
            </div>

            <!-- Rodapé -->
            <div class="footer">
                <p>Equipe {{ config('app.name') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
