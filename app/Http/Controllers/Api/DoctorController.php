<?php

namespace App\Http\Controllers\Api;

use App\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\PatientResource;
use App\Models\Appointment;
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


    // Homepage
    public function index(Request $request)
    { {
            $doctorId = $request->user()->doctor->id; // Get authenticated doctor ID

            // Get total number of appointments
            $totalAppointments = Appointment::where('doctor_id', $doctorId)->count();

            // Get count of appointments by status
            $pendingAppointmentsCount = Appointment::where('doctor_id', $doctorId)->where('status', 'pending')->count();
            $canceledAppointmentsCount = Appointment::where('doctor_id', $doctorId)->where('status', 'cancelled')->count();
            $completedAppointmentsCount = Appointment::where('doctor_id', $doctorId)->where('status', 'confirmed')->count();

            // Get all pending appointments
            $pendingAppointments = Appointment::where('doctor_id', $doctorId)
                ->where('status', 'pending')
                ->orderBy('appointment_date', 'asc')
                ->orderBy('appointment_time', 'asc')
                ->get();

            return response()->json([
                'status' => 'success',
                'total_appointments' => $totalAppointments,
                'pending_appointments_count' => $pendingAppointmentsCount,
                'canceled_appointments_count' => $canceledAppointmentsCount,
                'completed_appointments_count' => $completedAppointmentsCount,
                'pending_appointments' => AppointmentResource::collection($pendingAppointments)
            ]);
        }
    }
}
