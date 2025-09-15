<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use Illuminate\Http\Request;

class GeminiController extends Controller
{
    protected $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    public function ask(Request $request)
    {
        $prompt = $request->input('prompt');
        $answer = $this->gemini->ask($prompt);

        return response()->json([
            'prompt' => $prompt,
            'answer' => $answer
        ]);
    }
    public function listarModelos()
    {
        $modelos = app(\App\Services\GeminiService::class)->listarModelos();
        dd($modelos);
    }
}
