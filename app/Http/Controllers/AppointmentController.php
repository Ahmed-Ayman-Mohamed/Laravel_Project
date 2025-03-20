<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function bookAppointment(Request $request, $id)
    {
        $request->validate([
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i'
        ]);

        // Ensure the slot is still available
        $exists = Appointment::where('doctor_id', $id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'This time slot is already booked.'
            ], 422);
        }

        // Create appointment
        Appointment::create([
            'doctor_id' => $id,
            'patient_id' => $request->user()->patient->id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Appointment booked successfully.'
        ]);
    }



    public function getAvailableTimeSlots(Request $request, $doctorId)
    {
        // Get doctor's schedule for that day
        $dayOfWeek = date('l', strtotime($request->query('date')));

        $schedule = Schedule::where('doctor_id', $doctorId)
            ->where('available_days', $dayOfWeek) // Direct match instead of FIND_IN_SET
            ->first();

        if (!$schedule) {
            return response()->json([
                'status' => 'error',
                'message' => 'Doctor is not available on this day.'
            ], 404);
        }

        $startTime = strtotime($schedule->start_time);
        $endTime = strtotime($schedule->end_time);
        $slotDuration = 30 * 60; // 30 minutes per slot

        // Generate all possible time slots
        $availableSlots = [];
        for ($time = $startTime; $time < $endTime; $time += $slotDuration) {
            $availableSlots[] = date('H:i', $time);
        }

        // Fetch booked appointments for the given date
        $bookedSlots = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $request->query('date'))
            ->pluck('appointment_time')
            ->map(function ($time) {
                return date('H:i', strtotime($time)); // Ensure format matches available slots
            })
            ->toArray();


        // Filter out booked slots
        $freeSlots = array_diff($availableSlots, $bookedSlots);

        return response()->json([
            'status' => 'success',
            'available_slots' => array_values($freeSlots),
        ]);
    }

    public function getAppointmentsForPatient($status)
    {
        $patientId = Auth::user()->patient->id; // Get the authenticated patient's ID

        $appointments = Appointment::where('patient_id', $patientId)
            ->where('status', $status) // Assuming 'status' column exists
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            $status . '_appointments' => $appointments
        ]);
    }
}
