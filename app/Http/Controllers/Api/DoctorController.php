<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function me(Request $request)
    {
        return response()->json([
            //'doctor' => Auth::guard('doctor_api')->user(),
            // 'doctor' => auth::user(),
            'doctor' => $request->user(),
        ]);
    }
}
