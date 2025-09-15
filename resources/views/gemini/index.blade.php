@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Assistente Contábil (Gemini)</h1>
    </div>
    <div class="mb-3">
        {{-- Botão de voltar dinâmico --}}
        @php
            $user = auth()->user();
            $dashboardRoute = '#';

            if ($user) {
                if ($user->perfil === 'gerente') {
                    $dashboardRoute = route('gerentes.dashboard');
                } elseif ($user->perfil === 'funcionario') {
                    $dashboardRoute = route('funcionarios.dashboard');
                } elseif ($user->perfil === 'contratante') {
                    $dashboardRoute = route('contratantes.dashboard');
                }
            }
        @endphp

        @if($dashboardRoute !== '#')
            <a href="{{ $dashboardRoute }}" class="btn btn-secondary mb-3">Voltar</a>
        @endif
    </div>

    <div class="mb-3">
        <textarea id="prompt" class="form-control" rows="3" placeholder="Digite sua pergunta..."></textarea>
    </div>
    <button id="askBtn" class="btn btn-primary w-100">Perguntar</button>

    <div class="mt-4">
        <h5><strong>Resposta:</strong></h5>
        <p id="answer"></p>
    </div>
</div>

<script>
    document.getElementById('askBtn').addEventListener('click', async () => {
        const prompt = document.getElementById('prompt').value;
        const answerElement = document.getElementById('answer');

        answerElement.innerText = "Carregando...";

        try {
            const response = await fetch("{{ route('gemini.ask') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ prompt })
            });

            const data = await response.json();

            if (data.answer) {
                answerElement.innerText = data.answer;
            } else {
                answerElement.innerText = "Não recebi resposta do Gemini.";
            }
        } catch (error) {
            console.error(error);
            answerElement.innerText = "Erro ao processar a requisição.";
        }
    });
</script>
@endsection
