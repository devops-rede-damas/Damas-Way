<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\TogglesStatus;
use App\Models\Filial;

class FilialController extends Controller
{
    use TogglesStatus;

    public function index()
    {
        $filiais = Filial::with('coligada')->orderBy('status')->orderBy('id_rm')->get();

        return view('filiais.index', compact('filiais'));
    }

    public function toggleStatus(Filial $filial)
    {
        return $this->performToggleStatus($filial);
    }
}
