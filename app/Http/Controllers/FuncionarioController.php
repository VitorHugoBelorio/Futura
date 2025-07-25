<?php
namespace App\Http\Controllers;
use App\Models\Contratante;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FuncionarioController extends Controller
{
    public function index()
    {
        $funcionarios = User::where('perfil', 'funcionario')->get();
        return view('funcionarios.index', compact('funcionarios'));
    }

    public function create()
    {
        return view('funcionarios.create');
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
            'perfil' => 'funcionario',
        ]);

        return redirect()->route('funcionarios.index')->with('success', 'Funcionário criado com sucesso.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->perfil === 'funcionario') {
            $user->delete();
            return redirect()->route('funcionarios.index')->with('success', 'Funcionário excluído.');
        }
        abort(403);
    }

    public function edit($id)
    {
        $funcionario = User::where('perfil', 'funcionario')->findOrFail($id);
        return view('funcionarios.edit', compact('funcionario'));
    }

    public function update(Request $request, $id)
    {
        $funcionario = User::where('perfil', 'funcionario')->findOrFail($id);

        $request->validate([
            'nome' => 'required',
            'email' => 'required|email|unique:users,email,' . $funcionario->id,
            'senha' => 'nullable|min:6',
        ]);

        $funcionario->nome = $request->nome;
        $funcionario->email = $request->email;

        if ($request->filled('senha')) {
            $funcionario->password = Hash::make($request->senha);
        }

        $funcionario->save();

    return redirect()->route('funcionarios.index')->with('success', 'Funcionário atualizado com sucesso.');
}


    public function dashboard()
    {
        $contratantes = Contratante::all(); 
        return view('funcionarios.dashboard', compact('contratantes'));
    }
}
