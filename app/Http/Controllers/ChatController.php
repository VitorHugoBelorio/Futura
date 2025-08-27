<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function index()
    {
        $chat = session('chat', []);
        return view('chat.index', compact('chat'));
    }

    public function send(Request $request)
    {
        $userMessage = $request->input('message');
        $botText = 'Erro ao gerar resposta';

        try {
            $model = env('OLLAMA_MODEL', 'llama3.2:3b');
            $host  = env('OLLAMA_HOST', 'http://localhost:11434');
            
            // Instruções de sistema para personalizar o comportamento do agente
            $systemPrompt = "Você é um assistente virtual da Futura, uma empresa de contabilidade. "
                . "Seu nome é Futura AI. Seja sempre cordial, profissional e prestativo. "
                . "Forneça respostas claras, concisas e sem erros de português. Quando não souber a resposta, "
                . "seja honesto e sugira onde o usuário pode encontrar mais informações. "
                . "Evite respostas muito longas e mantenha um tom amigável.";
            
            // Opções avançadas para o modelo
            $options = [
                'temperature' => 0.7,    // Controla a criatividade (0.0 a 1.0)
                'top_p' => 0.9,         // Amostragem de núcleo (0.0 a 1.0)
                'top_k' => 40,          // Limita as opções de tokens
                'num_predict' => 256,    // Número máximo de tokens a gerar
            ];
            
            // Log para debug
            Log::info("Tentando conectar ao Ollama", [
                'host' => $host,
                'model' => $model,
                'message' => $userMessage,
                'system' => substr($systemPrompt, 0, 50) . '...'
            ]);

            $response = Http::timeout(600)->post("{$host}/api/generate", [
                'model'  => $model,
                'prompt' => $userMessage,
                'system' => $systemPrompt,
                'options' => $options,
                'stream' => false,
            ]);

            Log::info("Resposta do Ollama", [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->failed()) {
                $botText = 'Erro na chamada ao Ollama: ' . $response->body();
                Log::error("Falha na chamada ao Ollama", [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            } else {
                $json = $response->json();
                $botText = $json['response'] ?? 'Erro: resposta vazia do modelo';
            }

        } catch (\Exception $e) {
            $botText = 'Erro ao se conectar ao Ollama: ' . $e->getMessage();
            Log::error("Exceção ao conectar ao Ollama", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        // Atualiza histórico
        $chat = session('chat', []);
        $chat[] = ['sender' => 'user', 'text' => $userMessage];
        $chat[] = ['sender' => 'bot', 'text' => $botText];
        session(['chat' => $chat]);

        return back();
    }
}
