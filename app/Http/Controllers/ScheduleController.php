<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'schedules' => 'required|array',
            'schedules.*.available_days' => 'required|string',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i',
        ]);

        $doctorId = $request->user()->doctor->id; // Get the authenticated doctor ID

        $schedules = []; // This will hold the data to be inserted

        // Loop through the list of schedules in the request
        foreach ($request->schedules as $scheduleData) {
            $schedule = [
                'doctor_id' => $doctorId,
                'available_days' => $scheduleData['available_days'],
                'start_time' => $scheduleData['start_time'],
                'end_time' => $scheduleData['end_time'],
            ];

            // Add each schedule to the array
            $schedules[] = $schedule;
        }

        // Insert the schedules into the database
        Schedule::insert($schedules);

        return response()->json([
            'status' => 'success',
            'message' => 'Schedules created successfully.'
        ], 201);
    }

    public function show(Request $request)
    {
        $doctorId = $request->user()->doctor->id; // Get the authenticated doctor's ID

        $schedules = Schedule::where('doctor_id', $doctorId)->get();

        return response()->json([
            'status' => 'success',
            'schedules' => $schedules
        ]);
    }
}
