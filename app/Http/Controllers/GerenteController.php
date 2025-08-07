<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Contratante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;

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

        $user = User::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'password' => Hash::make($request->senha),
            'perfil' => 'gerente',
        ]);

        // Envia e-mail de redefinição de senha
        Password::sendResetLink(['email' => $user->email]);

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

        public function edit($id)
    {
        $gerente = User::where('perfil', 'gerente')->findOrFail($id);
        return view('gerentes.edit', compact('gerente'));
    }

    public function update(Request $request, $id)
    {
        $gerente = User::where('perfil', 'gerente')->findOrFail($id);

        $request->validate([
            'nome' => 'required',
            'email' => 'required|email|unique:users,email,' . $gerente->id,
            'senha' => 'nullable|min:6',
        ]);

        $gerente->nome = $request->nome;
        $gerente->email = $request->email;

        if ($request->filled('senha')) {
            $gerente->password = Hash::make($request->senha);
        }

        $gerente->save();

    return redirect()->route('gerentes.index')->with('success', 'Gerente atualizado com sucesso.');
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

        public function gerentes()
    {
        $gerentes = User::where('perfil', 'gerente')->get();
        return view('gerentes.index', compact('gerentes'));
    }
}
