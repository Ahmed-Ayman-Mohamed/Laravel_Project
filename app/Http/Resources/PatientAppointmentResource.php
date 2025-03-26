<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientAppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'doctor_name' => "Dr " . $this->doctor->user->first_name . " " . $this->doctor->user->last_name,
            'day' => $this->day,
            'status' => $this->status,
            'location' => $this->doctor->location,
            'phone' => $this->doctor->user->phone,
            'appointment_date' => $this->appointment_date,
            'appointment_time' => $this->appointment_time,
            //'patient' => new PatientDetailResource($this->patient->detail), // Include patient details
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
