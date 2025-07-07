<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\Contratante;

class UsarBancoDoContratante
{
    public function handle(Request $request, Closure $next)
    {
        $contratanteId = session('contratante_id');

        if (!$contratanteId) {
            return redirect()->route('selecionar.contratante')->with('error', 'Selecione um contratante.');
        }

        $contratante = Contratante::find($contratanteId);

        if (!$contratante) {
            abort(403, 'Contratante inválido.');
        }

        // Define dinamicamente o banco para tenant_temp
        Config::set('database.connections.tenant_temp.database', $contratante->banco_dados);

        // Testa a conexão (opcional, mas ajuda a evitar erro 500)
        try {
            DB::connection('tenant_temp')->getPdo();
        } catch (\Exception $e) {
            abort(500, 'Não foi possível conectar ao banco do contratante.');
        }

        return $next($request);
    }
}
