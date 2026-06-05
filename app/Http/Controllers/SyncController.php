<?php

namespace App\Http\Controllers;

use App\Services\TotvsRmService;

class SyncController extends Controller
{
    private $syncService;

    public function __construct(TotvsRmService $syncService)
    {
        $this->syncService = $syncService;
    }

    public function sincronizarColigadas()
    {
        $result = $this->syncService->sincronizarColigadas();

        return redirect()->back()->with(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );
    }

    public function sincronizarFiliais()
    {
        $result = $this->syncService->sincronizarFiliais();

        return redirect()->back()->with(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );
    }
}
