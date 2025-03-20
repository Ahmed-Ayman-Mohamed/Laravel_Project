<?php

use App\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class PatientController extends Controller
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

    public function doctorDetailsForBooking($id)
    {
        $doctor = Doctor::where('id', $id)->findOrFail();

        return $this->successResponse(DoctorResource::collection($doctor->user));
    }
}
