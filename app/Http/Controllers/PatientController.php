<?php

namespace App\Http\Controllers;

use App\Http\Resources\PatientAppointmentResource;
use App\Http\Resources\PatientDetailResource;
use App\Models\Appointment;
use App\Models\PatientDetail;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function show()
    {
        $patientDetail = PatientDetail::whereHas('patient', function ($query) {
            $query->where('patient_id', Auth::user()->patient->id);
        })->first();

        if (!$patientDetail) {
            return response()->json([
                'status' => 'error',
                'message' => 'Patient details not found.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'patient_detail' => new PatientDetailResource($patientDetail),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'age' => 'required|integer',
            'message' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $patient = Auth::user()->patient;
        if (!$patient) {
            return response()->json([
                'status' => 'error',
                'message' => 'Patient not found.'
            ], 404);
        }

        $data = $request->only(['name', 'age', 'message']);
        $data['patient_id'] = $patient->id;

        // if ($request->hasFile('image')) {
        //     $data['image'] = $request->file('image')->store('patient_images', 'public');
        // }

        $patientDetail = PatientDetail::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Patient details saved successfully.',
            'patient_detail' => new PatientDetailResource($patientDetail),
        ], 201);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string',
            'age' => 'nullable|integer',
            'message' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $patientDetail = PatientDetail::whereHas('patient', function ($query) {
            $query->where('patient_id', Auth::user()->patient->id);
        })->first();

        if (!$patientDetail) {
            return response()->json([
                'status' => 'error',
                'message' => 'Patient details not found.'
            ], 404);
        }

        if ($request->has('name')) {
            $patientDetail->name = $request->name;
        }
        if ($request->has('age')) {
            $patientDetail->age = $request->age;
        }
        if ($request->has('message')) {
            $patientDetail->message = $request->message;
        }


        // Saving X_ray Image {{Later}}
        // if ($request->hasFile('image')) {
        //     // Delete old image
        //     if ($patientDetail->image) {
        //         Storage::disk('public')->delete($patientDetail->image);
        //     }

        //     // Store new image
        //     $patientDetail->image = $request->file('image')->store('patient_images', 'public');
        // }

        $patientDetail->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Patient details updated successfully.',
            'patient_detail' => new PatientDetailResource($patientDetail),
        ]);
    }

    public function index(Request $request)
    {
        $patientId = $request->user()->patient->id; // Get authenticated patient ID

        $appointments = Appointment::where('patient_id', $patientId)
            ->where('status', 'pending') // Filter only pending appointments
            ->get();

        $specializations = Specialization::all();

        return response()->json([
            'status' => 'success',
            'pending_appointments' => PatientAppointmentResource::collection($appointments),
            'specializations' => $specializations
        ]);
    }
}
