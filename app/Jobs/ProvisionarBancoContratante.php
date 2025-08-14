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


class ProvisionarBancoContratante implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $contratanteId;
    public $userId;

    public function __construct(int $contratanteId, int $userId)
    {
        $this->contratanteId = $contratanteId;
        $this->userId = $userId;
    }

    public function handle()
    {
        $contratante = Contratante::findOrFail($this->contratanteId);
        $user = User::findOrFail($this->userId);

        // Criar banco
        $nomeBanco = (string) $contratante->id;
        DB::statement("CREATE DATABASE `$nomeBanco`");

        $contratante->update(['banco_dados' => $nomeBanco]);

        // Rodar migrations
        config(['database.connections.tenant_temp.database' => $nomeBanco]);
        Artisan::call('migrate', [
            '--path' => 'database/migrations/tenant',
            '--database' => 'tenant_temp',
            '--force' => true,
        ]);

        // Enviar e-mail
        Password::sendResetLink(['email' => $user->email]);
    }
}

