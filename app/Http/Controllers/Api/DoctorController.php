<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function me(Request $request)
    {
        $user = $request->user();
        // $doctor = $user->doctor;
        return response()->json([
            'doctor' => $user,
        ]);
    }
}
