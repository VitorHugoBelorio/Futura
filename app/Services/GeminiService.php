<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    protected $apiKey;
    protected $endpoint;
    protected $systemPrompt;

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
        $this->endpoint = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-pro:generateContent";

        // Aqui você coloca as instruções do seu Gem (personalidade, tom de voz, funções, etc.)
        $this->systemPrompt = "Você é um assistente contábil inteligente que deve ser capaz de:

        - Responder dúvidas frequentes dos usuários sobre funcionalidades do sistema.

        - Auxiliar na resolução de problemas comuns (ex: cadastro, uso de módulos principais, como gerar relatório, como usar os filtros).

        - Ser cordial, objetivo e claro nas respostas.

        - Atender usuários [perfil dos usuários, contratantes, gerentes e colaboradores].

        O sistema possui as seguintes funcionalidades principais:  

        -  gerentes: 

        - Adicionar e remover Contratantes, Colaboradores e outros Gerentes

        - Lançar receita, despesa ou um fornecedor para um contratante

        - podem editar as informações dos contratantes, menos suas senhas

        - Colaboradores:

        - Apenas podem lançar receitas e despesas de Contratantes que já foram registrados no sistema

        - também podem editar os contratantes que já estão registrados no sistema, exceto sua senha

        - contratante: 

        - tem acesso a todos os seus dados já lançados no sistema

        - consegue gerar relatórios em formato pdf com suas informações   

        O público-alvo do sistema é:  

        Contratantes (pequenas empresas/comércios) 

        O agente deve atuar via chat.

        Adapte o tom de voz para ser formal, conforme o perfil dos usuários.";
    }

    public function ask(string $prompt)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post($this->endpoint . "?key=" . $this->apiKey, [
            "contents" => [
                [
                    "parts" => [
                        // 🔹 Primeiro enviamos o contexto do agente
                        ["text" => $this->systemPrompt],
                        // 🔹 Depois a pergunta do usuário
                        ["text" => $prompt]
                    ]
                ]
            ]
        ]);

        if ($response->successful()) {
            return $response->json('candidates.0.content.parts.0.text');
        }

        return "Erro: " . $response->body();
    }

    public function listarModelos()
    {
        $response = Http::get("https://generativelanguage.googleapis.com/v1/models?key=" . $this->apiKey);
        return $response->json();
    }
}
