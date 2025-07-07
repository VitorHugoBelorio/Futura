<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contratante;

class ContratoAtivoController extends Controller
{
    public function index()
    {
        $contratantes = Contratante::all();
        return view('selecionar-contratante', compact('contratantes'));
    }

    public function definir(Request $request)
    {
        $request->validate([
            'contratante_id' => 'required|exists:contratantes,id',
        ]);

        session(['contratante_id' => $request->contratante_id]);

        return redirect()->route('fornecedores.index'); // ou outra rota desejada
    }
}
