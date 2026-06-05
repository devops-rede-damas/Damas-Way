<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait TogglesStatus
{
    protected function performToggleStatus(Model $model)
    {
        $model->status = $model->status == 1 ? 2 : 1;

        if (in_array('modificado_por', $model->getFillable())) {
            $model->modificado_por = Auth::id();
        }

        $model->save();

        return response()->json(['success' => true, 'status' => $model->status]);
    }
}
