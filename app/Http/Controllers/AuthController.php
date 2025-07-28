<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Contratante;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function loginAttempt(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); 

            $user = Auth::user(); 

            if ($user->perfil === 'contratante') {
                // Encontra o contratante com o mesmo email do user logado
                $contratante = Contratante::where('email', $user->email)->first();

                if ($contratante) {
                    session(['contratante_id' => $contratante->id]);
                    return redirect()->route('contratante.dashboard');
                } else {
                    Auth::logout();
                    return redirect()->route('login')->withErrors([
                        'email' => 'Contratante não encontrado.',
                    ]);
                }
            }

            if ($user->perfil === 'gerente') {
                return redirect()->route('gerentes.dashboard');
            } elseif ($user->perfil === 'funcionario') {
                return redirect()->route('funcionarios.dashboard');
            } 
        }

        // Falha na autenticação
        return back()->withInput()->withErrors([
            'error' => 'Login inválido.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
