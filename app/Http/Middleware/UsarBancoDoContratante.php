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

        // Garante nova conexão para evitar cache de conexões anteriores
        DB::purge('tenant_temp');

        Config::set('database.connections.tenant_temp.database', $contratante->banco_dados);

        try {
            DB::reconnect('tenant_temp');
            DB::connection('tenant_temp')->getPdo(); // força verificação
        } catch (\Exception $e) {
            report($e);
            abort(500, 'Erro ao conectar com o banco do contratante.');
        }

        return $next($request);
    }
}
