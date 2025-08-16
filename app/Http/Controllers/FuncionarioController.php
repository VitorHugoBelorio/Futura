<?php
namespace App\Http\Controllers;
use App\Models\Contratante;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class FuncionarioController extends Controller
{
    public function index()
    {
        try {
            $funcionarios = User::where('perfil', 'funcionario')->get();
            return view('funcionarios.index', compact('funcionarios'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar funcionários: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            return view('funcionarios.create');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar formulário: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
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
                'perfil' => 'funcionario',
            ]);

            // Envia e-mail de redefinição de senha
            Password::sendResetLink(['email' => $user->email]);

            return redirect()->route('funcionarios.index')->with('success', 'Funcionário criado com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao criar funcionário: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            if ($user->perfil === 'funcionario') {
                $user->delete();
                return redirect()->route('funcionarios.index')->with('success', 'Funcionário excluído.');
            }
            abort(403);
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir funcionário: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $funcionario = User::where('perfil', 'funcionario')->findOrFail($id);
            return view('funcionarios.edit', compact('funcionario'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar funcionário: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $funcionario = User::where('perfil', 'funcionario')->findOrFail($id);

            $request->validate([
                'nome' => 'required',
                'email' => 'required|email|unique:users,email,' . $funcionario->id,
            ]);

            $funcionario->nome = ucfirst(strtolower(trim($request->nome)));
            $funcionario->email = strtolower(trim($request->email));

            $funcionario->save();

            return redirect()->route('funcionarios.index')->with('success', 'Funcionário atualizado com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar funcionário: ' . $e->getMessage());
        }
    }

    public function dashboard(Request $request)
    {
        try {
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

            return view('funcionarios.dashboard', compact('contratantes'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar dashboard: ' . $e->getMessage());
        }
    }
}
