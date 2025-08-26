@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Assistente Virtual</h1>
        <p class="text-muted">Converse com o agente de IA para tirar dúvidas e receber suporte.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white fw-semibold">
            Chat com IA
        </div>

        <div class="card-body" id="chat-box" 
             style="height: 400px; overflow-y: auto; background: #f8f9fa;">

            @if(!empty($chat))
                @foreach($chat as $message)
                    @if($message['sender'] === 'user')
                        <div class="d-flex justify-content-end mb-2">
                            <div class="p-2 rounded-3 bg-primary text-white shadow-sm" style="max-width: 70%;">
                                {{ $message['text'] }}
                            </div>
                        </div>
                    @else
                        <div class="d-flex justify-content-start mb-2">
                            <div class="p-2 rounded-3 bg-light border shadow-sm" style="max-width: 70%;">
                                {{ $message['text'] }}
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <p class="text-center text-muted mt-4">Inicie uma conversa enviando uma mensagem abaixo.</p>
            @endif

        </div>

        <div class="card-footer bg-white">
            <form method="POST" action="{{ route('chat.send') }}" class="d-flex" id="chat-form">
                @csrf
                <input type="text" name="message" 
                       class="form-control me-2" 
                       placeholder="Digite sua mensagem..." 
                       required autocomplete="off" maxlength="500">
                <button class="btn btn-success">Enviar</button>
            </form>
        </div>
    </div>
</div>

{{-- Scroll automático e limpeza do input --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const chatBox = document.getElementById("chat-box");
    const form = document.getElementById('chat-form');

    function scrollToBottom() {
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    // scroll ao carregar
    scrollToBottom();

    // scroll e limpa input ao enviar
    form.addEventListener('submit', function() {
        setTimeout(scrollToBottom, 100); // espera resposta renderizar
        this.message.value = ''; // limpa input
    });
});
</script>
@endsection
