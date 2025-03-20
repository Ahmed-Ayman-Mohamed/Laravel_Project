<?php

namespace App\Http\Controllers\Api;

use App\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\PatientResource;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    use ApiResponseTrait;
    public function me(Request $request)
    {
        $user = $request->user();
        // $doctor = $user->doctor;
        return response()->json([
            'doctor' => $user,
        ]);
    }
    public function userSpecialization(Request $request)
    {
        $user = $request->user();
        $user = User::with('doctor.specializations')->find($user->id);
        $doctor = $user->doctor;
        return response()->json([
            'doctor' => $user,
        ]);
    }
    public function getAllDoctors()
    {
        $users = User::where('role', 'doctor')->get(); // doctors
        return $this->successResponse(DoctorResource::collection($users));
    }
    public function getAllPatients()
    {
        $users = User::where('role', 'patient')->get(); // patients
        return $this->successResponse(PatientResource::collection($users));
    }

    public function filter(Request $request)
    {
        $filters = $request->only(['review_rating', 'min_price', 'max_price']);

        // Use the filter method from the Doctor model
        $doctors = Doctor::filter($filters)->get();

        // return response()->json([
        //     'status' => 'success',
        //     'data' => $doctors
        // ], 200);
        $users = [];
        foreach ($doctors as $doctor) {
            $user = $doctor->user;
            $users[] = $user;
        }

        return $this->successResponse(DoctorResource::collection($users));
    }

    public function doctorDetailsForBooking($id)
    {
        $doctor = Doctor::with('user')->findOrFail($id);

        return $this->successResponse(new DoctorResource($doctor->user));
    }
}
