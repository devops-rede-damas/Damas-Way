<?php

namespace App\Console\Commands;

use App\Services\TotvsRmService;
use Illuminate\Console\Command;

class SyncTotvs extends Command
{
    protected $signature = 'sync:totvs {--only= : Sincronizar apenas: coligadas, filiais}';

    protected $description = 'Sincroniza coligadas e filiais com API TOTVS RM';

    private $entidades = [
        'coligadas' => 'sincronizarColigadas',
        'filiais'   => 'sincronizarFiliais',
    ];

    public function handle(TotvsRmService $service)
    {
        $only = $this->option('only');

        $this->info('');
        $this->info('╔══════════════════════════════════════════════════╗');
        $this->info('║      Damas Way — Sincronização TOTVS RM         ║');
        $this->info('║      Data: ' . now()->format('d/m/Y H:i:s') . str_repeat(' ', 18) . '║');
        $this->info('╚══════════════════════════════════════════════════╝');
        $this->info('');

        if ($only) {
            if (!isset($this->entidades[$only])) {
                $this->error("Opção inválida: '{$only}'. Use: coligadas, filiais");
                return 1;
            }

            $method = $this->entidades[$only];
            $this->line("▸ Sincronizando {$only}...");
            $result = $service->$method();
            $this->outputResult($only, $result);
        } else {
            $results = $service->sincronizarTudo();
            foreach ($results as $entidade => $result) {
                $this->outputResult($entidade, $result);
            }
        }

        $this->info('');
        $this->info('Sincronização finalizada em ' . now()->format('d/m/Y H:i:s'));

        return 0;
    }

    private function outputResult(string $entidade, array $result): void
    {
        $label = str_pad(ucfirst($entidade), 12);

        if ($result['success']) {
            $this->info("  ✓ {$label} {$result['message']}");
        } else {
            $this->error("  ✗ {$label} {$result['message']}");
        }
    }
}
