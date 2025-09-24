@extends('layouts.app')

@section('content')
<div class="container my-5 d-flex justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-4 p-4">
            
            {{-- Cabeçalho --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold m-0">Assistente Contábil <small class="text-muted">(Gemini)</small></h2>
                @php
                    $user = auth()->user();
                    $dashboardRoute = '#';

                    if ($user) {
                        if ($user->perfil === 'gerente') {
                            $dashboardRoute = route('gerentes.dashboard');
                        } elseif ($user->perfil === 'funcionario') {
                            $dashboardRoute = route('funcionarios.dashboard');
                        } elseif ($user->perfil === 'contratante') {
                            $dashboardRoute = route('contratante.dashboard');
                        }
                    }
                @endphp

                @if($dashboardRoute !== '#')
                    <a href="{{ $dashboardRoute }}" class="btn btn-light btn-sm rounded-pill shadow-sm">← Voltar</a>
                @endif
            </div>

            {{-- Campo de pergunta --}}
            <div class="mb-3">
                <textarea id="prompt" class="form-control rounded-3 shadow-sm border-0" rows="3" placeholder="Digite sua pergunta..."></textarea>
            </div>

            <button id="askBtn" class="btn btn-primary w-100 rounded-3 py-2 shadow-sm">Perguntar</button>

            {{-- Resposta --}}
            <div class="mt-4">
                <h6 class="fw-bold text-muted mb-2">Resposta</h6>
                <div class="p-3 rounded-3 bg-light border" id="answer" style="min-height: 60px;"></div>
            </div>
        </div>
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
