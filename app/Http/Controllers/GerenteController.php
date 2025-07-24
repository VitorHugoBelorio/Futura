<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Contratante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GerenteController extends Controller
{
    public function index()
    {
        $gerentes = User::where('perfil', 'gerente')->get();
        return view('gerentes.index', compact('gerentes'));
    }

    public function create()
    {
        return view('gerentes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'email' => 'required|email|unique:users,email',
            'senha' => 'required|min:6',
        ]);

        User::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'password' => Hash::make($request->senha),
            'perfil' => 'gerente',
        ]);

        return redirect()->route('gerentes.index')->with('success', 'Gerente criado com sucesso.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->perfil === 'gerente') {
            $user->delete();
            return redirect()->route('gerentes.index')->with('success', 'Gerente excluído.');
        }
        abort(403);
    }

    public function dashboard()
    {
        $contratantes = Contratante::all(); 
        return view('gerentes.dashboard', compact('contratantes'));
    }

    public function funcionarios()
    {
        $funcionarios = User::where('perfil', 'funcionario')->get();
        return view('funcionarios.index', compact('funcionarios'));
    }
}
