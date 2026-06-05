<?php

namespace App\Services;

use App\Models\Coligada;
use App\Models\Filial;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TotvsRmService
{
    private function consultarTotvs(string $url): array
    {
        $user = config('damasway.totvs_api_user');
        $pass = config('damasway.totvs_api_pass');

        try {
            $response = Http::withBasicAuth($user, $pass)
                ->timeout(120)
                ->acceptJson()
                ->get($url);

            return ['response' => $response->body(), 'httpCode' => $response->status()];
        } catch (\Exception $e) {
            Log::error("TotvsRmService HTTP error: {$e->getMessage()}");
            return ['response' => null, 'httpCode' => 0];
        }
    }

    // =========================================================================
    // COLIGADAS
    // =========================================================================

    public function sincronizarColigadas(): array
    {
        set_time_limit(120);

        $base = config('damasway.totvs_api_base_url');
        $url = $base . '/rmsrestdataserver/rest/GlbColigadaDataBR?start=0&limit=0';

        try {
            $resultado = $this->consultarTotvs($url);

            if ($resultado['httpCode'] !== 200 || !$resultado['response']) {
                Log::error('sincronizarColigadas: Erro API TOTVS. HTTP: ' . $resultado['httpCode']);
                return ['success' => false, 'message' => 'Erro ao consultar API TOTVS. HTTP: ' . $resultado['httpCode']];
            }

            $json = json_decode($resultado['response'], true);
            $dadosTotvs = $json['data'] ?? $json;

            if (!is_array($dadosTotvs)) {
                return ['success' => false, 'message' => 'Resposta inválida da API TOTVS.'];
            }

            // Indexar dados do TOTVS por CODCOLIGADA
            $totvsIndexado = [];
            foreach ($dadosTotvs as $item) {
                $totvsIndexado[$item['CODCOLIGADA']] = $item;
            }

            // Buscar registros locais indexados por id_rm
            $locais = Coligada::all()->keyBy('id_rm');

            $atualizados = 0;
            $adicionados = 0;
            $desativados = 0;
            $agora = now();

            DB::beginTransaction();

            foreach ($totvsIndexado as $codRm => $totvs) {
                // Só insere se não existe localmente
                if (!isset($locais[$codRm])) {
                    $nome = $totvs['NOME'] ?? $totvs['NOMEFANTASIA'] ?? '';
                    DB::table('coligadas')->insert([
                        'id_rm'     => $codRm,
                        'coligada'  => $nome,
                        'status'    => 1,
                        'criado_em' => $agora,
                    ]);
                    $adicionados++;
                }
            }

            // Desativar coligadas que não existem mais no TOTVS
            foreach ($locais as $idRm => $local) {
                if (!isset($totvsIndexado[$idRm]) && $local->status == 1) {
                    DB::table('coligadas')->where('id', $local->id)->update([
                        'status'        => 2,
                        'modificado_em' => $agora,
                    ]);
                    $desativados++;
                }
            }

            DB::commit();

            $stats = "Atualizados: {$atualizados}, Adicionados: {$adicionados}, Desativados: {$desativados}";
            Log::info("sincronizarColigadas: {$stats}");

            return ['success' => true, 'message' => "Coligadas sincronizadas! {$stats}"];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('sincronizarColigadas: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Erro ao sincronizar coligadas: ' . $e->getMessage()];
        }
    }

    // =========================================================================
    // FILIAIS
    // =========================================================================

    public function sincronizarFiliais(): array
    {
        set_time_limit(120);

        $base = config('damasway.totvs_api_base_url');
        $url = $base . '/api/framework/v1/Branches?' . urlencode('$filter') . '=' . urlencode('Active eq 1');

        try {
            $resultado = $this->consultarTotvs($url);

            if ($resultado['httpCode'] !== 200 || !$resultado['response']) {
                Log::error('sincronizarFiliais: Erro API TOTVS. HTTP: ' . $resultado['httpCode']);
                return ['success' => false, 'message' => 'Erro ao consultar API TOTVS. HTTP: ' . $resultado['httpCode']];
            }

            $json = json_decode($resultado['response'], true);
            $dadosTotvs = $json['items'] ?? $json;

            if (!is_array($dadosTotvs)) {
                return ['success' => false, 'message' => 'Resposta inválida da API TOTVS.'];
            }

            // Mapear coligadas locais: id_rm → id (PK)
            $coligadasMap = Coligada::pluck('id', 'id_rm')->toArray();

            // Indexar dados TOTVS por Code_CompanyCode
            $totvsIndexado = [];
            foreach ($dadosTotvs as $item) {
                $chave = $item['Code'] . '_' . $item['CompanyCode'];
                $totvsIndexado[$chave] = $item;
            }

            // Indexar filiais locais por id_rm_id_rm_coligada
            $locais = [];
            foreach (Filial::with('coligada')->get() as $f) {
                $coligadaIdRm = $f->coligada ? $f->coligada->id_rm : null;
                if ($coligadaIdRm) {
                    $chave = $f->id_rm . '_' . $coligadaIdRm;
                    $locais[$chave] = $f;
                }
            }

            $adicionados = 0;
            $desativados = 0;
            $agora = now();

            DB::beginTransaction();

            foreach ($totvsIndexado as $chave => $totvs) {
                // Só insere se não existe localmente
                if (!isset($locais[$chave])) {
                    $idColigadaLocal = $coligadasMap[$totvs['CompanyCode']] ?? null;

                    if ($idColigadaLocal) {
                        DB::table('filiais')->insert([
                            'id_rm'        => $totvs['Code'],
                            'id_coligada'  => $idColigadaLocal,
                            'filial'       => $totvs['Title'],
                            'endereco'     => json_encode([]),
                            'status'       => 1,
                            'criado_em'    => $agora,
                        ]);
                        $adicionados++;
                    }
                }
            }

            // Desativar filiais que não existem mais no TOTVS
            foreach ($locais as $chave => $local) {
                if (!isset($totvsIndexado[$chave]) && $local->status == 1) {
                    DB::table('filiais')->where('id', $local->id)->update([
                        'status'        => 2,
                        'modificado_em' => $agora,
                    ]);
                    $desativados++;
                }
            }

            DB::commit();

            $stats = "Adicionados: {$adicionados}, Desativados: {$desativados}";
            Log::info("sincronizarFiliais: {$stats}");

            return ['success' => true, 'message' => "Filiais sincronizadas! {$stats}"];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('sincronizarFiliais: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Erro ao sincronizar filiais: ' . $e->getMessage()];
        }
    }

    // =========================================================================
    // SINCRONIZAR TUDO
    // =========================================================================

    public function sincronizarTudo(): array
    {
        return [
            'coligadas' => $this->sincronizarColigadas(),
            'filiais'   => $this->sincronizarFiliais(),
        ];
    }
}
