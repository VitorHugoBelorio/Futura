<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Contratante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class GerenteController extends Controller
{
    public function index()
    {
        try {
            $gerentes = User::where('perfil', 'gerente')
                            ->where('status', 'ativo') // Apenas gerentes ativos
                            ->get();
            return view('gerentes.index', compact('gerentes'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar gerentes: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            return view('gerentes.create');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar formulário: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nome' => 'required',
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    'unique:users,email',
                ],
            ]);

            $senhaAleatoria = Str::random(12);
            $nomeNormalizado = ucwords(mb_strtolower(trim($request->nome), 'UTF-8'));
            $emailNormalizado = mb_strtolower(trim($request->email), 'UTF-8');

            $user = User::create([
                'nome' => $nomeNormalizado,
                'email' => $emailNormalizado,
                'password' => Hash::make($senhaAleatoria),
                'perfil' => 'gerente',
                'status' => 'ativo', // garante que o gerente é criado como ativo
            ]);

            Password::sendResetLink(['email' => $user->email]);

            return redirect()->route('gerentes.index')->with('success', 'Gerente criado com sucesso.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors(['email' => 'Este e-mail já está cadastrado no sistema.'])->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao criar gerente: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            if ($user->perfil === 'gerente') {
                $user->status = 'inativo'; // apenas inativa o gerente
                $user->save();
                return redirect()->route('gerentes.index')->with('success', 'Gerente desativado.');
            }
            abort(403);
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao desativar gerente: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $gerente = User::where('perfil', 'gerente')->findOrFail($id);
            return view('gerentes.edit', compact('gerente'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar gerente: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
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
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar gerente: ' . $e->getMessage());
        }
    }

    public function dashboard(Request $request)
    {
        try {
            $query = Contratante::where('status', 'ativo'); // Apenas ativos

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
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar dashboard: ' . $e->getMessage());
        }
    }

    public function funcionarios()
    {
        try {
            $funcionarios = User::where('perfil', 'funcionario')->get();
            return view('funcionarios.index', compact('funcionarios'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar funcionários: ' . $e->getMessage());
        }
    }

    public function gerentes()
    {
        try {
            $gerentes = User::where('perfil', 'gerente')->get();
            return view('gerentes.index', compact('gerentes'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar gerentes: ' . $e->getMessage());
        }
    }

    public function desativados()
    {
        // Apenas gerente pode acessar
        if (!auth()->user() || auth()->user()->perfil !== 'gerente') {
            abort(403);
        }

        $gerentes = User::where('perfil', 'gerente')
                        ->where('status', 'inativo')
                        ->get();

        return view('gerentes.desativados', compact('gerentes'));
    }

    public function reativar($id)
    {
        if (!auth()->user() || auth()->user()->perfil !== 'gerente') {
            abort(403);
        }

        $gerente = User::where('perfil', 'gerente')->findOrFail($id);
        $gerente->status = 'ativo';
        $gerente->save();

        return redirect()->route('gerentes.desativados')->with('success', 'Gerente reativado com sucesso.');
    }
}
