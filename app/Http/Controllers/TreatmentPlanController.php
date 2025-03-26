<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\TreatmentPlan;
use Illuminate\Http\Request;

class TreatmentPlanController extends Controller
{
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'appointment_id' => 'required|exists:appointments,id',
    //         'name' => 'required|string|max:255',
    //         'date' => 'required|date',
    //         'status' => 'boolean'
    //     ]);

    //     $treatmentPlan = TreatmentPlan::create($request->all());

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Treatment plan created successfully.',
    //         'treatment_plan' => $treatmentPlan
    //     ], 201);
    // }

    public function getPatientTreatmentPlans(Request $request, $patientId)
    {
        $doctorId = $request->user()->doctor->id; // Get authenticated doctor's ID

        // Fetch the treatment plans for the given patient created by this doctor
        $treatmentPlans = TreatmentPlan::where('patient_id', $patientId)
            ->where('doctor_id', $doctorId)
            ->orderBy('date', 'desc') // Sort by date (latest first)
            ->get(['id', 'name', 'status', 'date']);

        // If no treatment plans found, return an error
        if ($treatmentPlans->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No treatment plans found for this patient.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'treatment_plans' => $treatmentPlans,
        ]);
    }

    public function createTreatmentPlan(Request $request, $patientId)
    {
        $doctorId = $request->user()->doctor->id; // Get authenticated doctor's ID

        // Validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'status' => 'boolean',
        ]);

        // Find the first appointment time for this patient with this doctor
        $firstAppointment = Appointment::where('doctor_id', $doctorId)
            ->where('patient_id', $patientId)
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->first();

        if (!$firstAppointment) {
            return response()->json([
                'status' => 'error',
                'message' => 'No previous appointment found for this patient.',
            ], 422);
        }

        // Create the treatment plan
        $treatmentPlan = TreatmentPlan::create([
            'doctor_id' => $doctorId,
            'patient_id' => $patientId,
            'name' => $request->name,
            'date' => $request->date,
            'status' => $request->status ?? false, // Default status to false if not provided
        ]);

        // Create a new appointment using the first appointment's time
        $appointment = Appointment::create([
            'doctor_id' => $doctorId,
            'patient_id' => $patientId,
            'appointment_date' => $request->date, // Use treatment plan date
            'appointment_time' => $firstAppointment->appointment_time, // Use first appointment's time
            'status' => 'pending', // Default to pending
            'treatment_plan_id' => $treatmentPlan->id, // Associate with the treatment plan
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Treatment plan and appointment created successfully.',
            'treatment_plan' => $treatmentPlan,
            'appointment' => $appointment
        ], 201);
    }
}
