<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat.index');
    }

    public function send(Request $request)
    {
        $userMessage = $request->input('message');

        $botText = 'Erro ao gerar resposta';

        try {
            $model = env('OLLAMA_MODEL', 'llama3.2:3b');
            $host  = env('OLLAMA_HOST', 'http://localhost:11434');

            $response = Http::timeout(300)->post("{$host}/api/generate", [
                'model'  => $model,
                'prompt' => $userMessage,
                'stream' => false,
            ]);

            $json = $response->json();
            $botText = $json['response'] ?? 'Erro: resposta vazia do modelo';

        } catch (\Exception $e) {
            $botText = 'Erro ao se conectar ao Ollama: ' . $e->getMessage();
        }

        $chat = session('chat', []);
        $chat[] = ['sender' => 'user', 'text' => $userMessage];
        $chat[] = ['sender' => 'bot', 'text' => $botText];
        session(['chat' => $chat]);

        return back();
    }
}
