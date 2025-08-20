<?php

namespace App\Http\Controllers;

use App\Models\Contratante;
use App\Models\User;
use App\Models\Despesa;
use App\Models\Fornecedor;
use App\Models\Receita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use App\Jobs\ProvisionarBancoContratante;

class ContratanteController extends Controller
{
    public function index()
    {
        try {
            $contratantes = Contratante::where('status', 'ativo')->get();
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao buscar contratantes: ' . $e->getMessage());
        }
        return view('contratantes.index', compact('contratantes'));
    }

    public function create()
    {
        return view('contratantes.create');
    }

    public function store(Request $request)
    {
        try {
            $request->merge([
                'cnpj' => preg_replace('/\D/', '', $request->cnpj)
            ]);

            $request->validate([
                'nome' => 'required|string|max:255',
                'cnpj' => 'required|string|max:18|unique:contratantes,cnpj',
                'email' => [
                    'required',
                    'email',
                    'unique:users,email', // Garante que não existe em users
                    'unique:contratantes,email', // Garante que não existe em contratantes
                ],
                'telefone' => 'nullable|string|max:20',
            ]);

            // Verifica se o e-mail já existe na tabela users
            if (User::where('email', strtolower(trim($request->email)))->exists()) {
                return back()
                    ->withErrors(['email' => 'Este e-mail já está cadastrado no sistema.'])
                    ->withInput();
            }

            $senhaAleatoria = Str::random(12);

            $user = User::create([
                'nome'     => $request->nome,
                'email'    => strtolower(trim($request->email)),
                'password' => Hash::make($senhaAleatoria),
                'perfil'   => 'contratante',
            ]);

            $contratante = Contratante::create([
                'nome'        => $request->nome,
                'cnpj'        => $request->cnpj,
                'email'       => strtolower(trim($request->email)),
                'telefone'    => $request->telefone,
                'user_id'     => $user->id,
                'banco_dados' => '',
            ]);

            // Dispara o job em segundo plano
            ProvisionarBancoContratante::dispatchSync($contratante->id, $user->id);

            return redirect()->route('gerentes.dashboard')
                ->with('success', 'Contratante criado com sucesso! O banco será provisionado em instantes.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withErrors(['email' => 'Este e-mail já está cadastrado no sistema.'])
                ->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao criar contratante: ' . $e->getMessage());
        }
    }

    public function show(Contratante $contratante, Request $request)
    {
        if ($contratante->status !== 'ativo') {
            return back()->with('error', 'Este contratante está inativo.');
        }


        session(['contratante_id' => $contratante->id]);
        config(['database.connections.tenant_temp.database' => $contratante->banco_dados]);

        try {
            DB::connection('tenant_temp')->getPdo();

            $dataInicio = $request->input('data_inicio') ?? Carbon::now()->startOfMonth()->toDateString();
            $dataFim = $request->input('data_fim') ?? Carbon::now()->endOfMonth()->toDateString();

            $receitas = (new Receita())
                ->setConnection('tenant_temp')
                ->whereBetween('data_recebimento', [$dataInicio, $dataFim])
                ->get();

            $despesas = (new Despesa())
                ->setConnection('tenant_temp')
                ->whereBetween('data_pagamento', [$dataInicio, $dataFim])
                ->with('fornecedor')
                ->get();

            $fornecedores = (new Fornecedor())
                ->setConnection('tenant_temp')
                ->get();

            $saldo = $receitas->sum('valor') - $despesas->sum('valor');

        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao conectar com o banco do contratante: ' . $e->getMessage());
        }

        return view('contratantes.show', compact(
            'contratante',
            'receitas',
            'despesas',
            'fornecedores',
            'saldo',
            'dataInicio',
            'dataFim'
        ));
    }

    public function edit(Contratante $contratante)
    {
        if ($contratante->status !== 'ativo') {
            return back()->with('error', 'Não é possível editar um contratante inativo.');
        }

        try {
            return view('contratantes.edit', compact('contratante'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar edição: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Contratante $contratante)
    {
        if ($contratante->status !== 'ativo') {
            return back()->with('error', 'Não é possível atualizar um contratante inativo.');
        }

        try {
            $request->merge([
                'cnpj' => preg_replace('/\D/', '', $request->cnpj)
            ]);

            $request->validate([
                'nome'     => 'required|string|max:255',
                'cnpj'     => 'required|string|max:18|unique:contratantes,cnpj,' . $contratante->id,
                'email'    => 'required|email|unique:contratantes,email,' . $contratante->id,
                'telefone' => 'nullable|string|max:20',
            ]);

            $contratante->update([
                'nome'     => $request->nome,
                'cnpj'     => $request->cnpj,
                'email'    => strtolower(trim($request->email)),
                'telefone' => $request->telefone,
            ]);

            if ($contratante->user_id) {
                $user = User::find($contratante->user_id);

                if ($user) {
                    $user->nome  = $request->nome;
                    $user->email = strtolower(trim($request->email));
                    $user->save();
                }
            }

            $rota = auth()->user()->isGerente() ? 'gerentes.dashboard' : 'funcionarios.dashboard';

            return redirect()->route($rota)
                ->with('success', 'Contratante atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar contratante: ' . $e->getMessage());
        }
    }

    public function destroy(Contratante $contratante)
    {
        try {
            // Atualiza status para inativo
            $contratante->status = 'inativo';
            $contratante->save();

            // Também inativa o usuário vinculado
            if ($contratante->user_id) {
                $user = User::find($contratante->user_id);
                if ($user) {
                    $user->status = 'inativo';
                    $user->save();
                }
            }

            return redirect()->route('gerentes.dashboard')
                            ->with('success', 'Contratante desativado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('gerentes.dashboard')
                            ->with('error', 'Erro ao desativar contratante: ' . $e->getMessage());
        }
    }

    public function desativados()
    {
        if (!auth()->user() || auth()->user()->perfil !== 'gerente') {
            abort(403);
        }

        $contratantes = Contratante::where('status', 'inativo')->get();
        return view('contratantes.desativados', compact('contratantes'));
    }

    public function reativar($id)
    {
        if (!auth()->user() || auth()->user()->perfil !== 'gerente') {
            abort(403);
        }

        $contratante = Contratante::findOrFail($id);
        $contratante->status = 'ativo';
        $contratante->save();

        // Reativa também o usuário vinculado, se existir
        if ($contratante->user_id) {
            $user = User::find($contratante->user_id);
            if ($user) {
                $user->status = 'ativo';
                $user->save();
            }
        }

        return redirect()->route('contratantes.desativados')->with('success', 'Contratante reativado com sucesso!');
    }
}
