<?php
namespace App\Jobs;

use App\Models\Contratante;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProvisionarBancoContratante implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $contratanteId;
    public $userId;
    public $tries = 3; // Número de tentativas

    public function __construct(int $contratanteId, int $userId)
    {
        $this->contratanteId = $contratanteId;
        $this->userId = $userId;
    }

    public function handle()
    {
        try {
            $contratante = Contratante::findOrFail($this->contratanteId);
            $user = User::findOrFail($this->userId);

            // Criar banco
            $nomeBanco = 'tenant_' . $contratante->id;
            Log::info("Criando banco de dados: {$nomeBanco}");
            
            // Usar credenciais do root para criar o banco
            DB::statement("CREATE DATABASE IF NOT EXISTS `$nomeBanco`");

            $contratante->update(['banco_dados' => $nomeBanco]);

            // Rodar migrations
            Log::info("Executando migrações para o banco: {$nomeBanco}");
            config(['database.connections.tenant_temp.database' => $nomeBanco]);
            Artisan::call('migrate', [
                '--path' => 'database/migrations/tenant',
                '--database' => 'tenant_temp',
                '--force' => true,
            ]);
            
            Log::info("Migrações concluídas para: {$nomeBanco}");

            // Enviar e-mail
            Log::info("Enviando e-mail para: {$user->email}");
            Password::sendResetLink(['email' => $user->email]);
            Log::info("E-mail enviado com sucesso");
            
        } catch (\Exception $e) {
            Log::error("Erro ao provisionar banco: " . $e->getMessage());
            throw $e;
        }
    }
}

