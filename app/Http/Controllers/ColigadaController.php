<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\TogglesStatus;
use App\Models\Coligada;

class ColigadaController extends Controller
{
    use TogglesStatus;

    public function index()
    {
        $coligadas = Coligada::orderBy('coligada')->get();

        return view('coligadas.index', compact('coligadas'));
    }

    public function toggleStatus(Coligada $coligada)
    {
        return $this->performToggleStatus($coligada);
    }
}
