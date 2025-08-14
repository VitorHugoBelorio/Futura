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
        ]);

        // Gera senha aleatória
        $senhaAleatoria = Str::random(12);

        // Normalização dos dados
        $nomeNormalizado = ucwords(mb_strtolower(trim($request->nome), 'UTF-8'));
        $emailNormalizado = mb_strtolower(trim($request->email), 'UTF-8');

        $user = User::create([
            'nome' => $nomeNormalizado,
            'email' => $emailNormalizado,
            'password' => Hash::make($senhaAleatoria),
            'perfil' => 'gerente',
        ]);

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
        ]);

        // Normalização
        $gerente->nome = ucwords(mb_strtolower(trim($request->nome), 'UTF-8'));
        $gerente->email = mb_strtolower(trim($request->email), 'UTF-8');

        $gerente->save();

        return redirect()->route('gerentes.index')->with('success', 'Gerente atualizado com sucesso.');
    }

    public function dashboard(Request $request)
    {
        $query = Contratante::query();

        // Captura os valores do formulário
        $search = $request->input('search');
        $filtro = $request->input('filtro');

        // Se tiver pesquisa
        if (!empty($search)) {
            if (!empty($filtro)) {
                // Pesquisa em um campo específico
                $query->where($filtro, 'LIKE', '%' . $search . '%');
            } else {
                // Pesquisa em todos os campos relevantes
                $query->where(function ($q) use ($search) {
                    $q->where('nome', 'LIKE', '%' . $search . '%')
                    ->orWhere('cnpj', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%')
                    ->orWhere('telefone', 'LIKE', '%' . $search . '%');
                });
            }
        }

        // Ordena e pagina
        $contratantes = $query->orderBy('nome')
                            ->paginate(10)
                            ->appends($request->all());

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
