<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
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
    public function getAllDoctors(){
        $doctors = User::where('role', 'doctor')->with('patient')->get();
        return response()->json(['doctors' => $doctors]);
    }
    public function getAllPatients(){
        $patients = User::where('role', 'patient')->with('patient')->get();
        return response()->json(['patients' => $patients]);
    }
}
