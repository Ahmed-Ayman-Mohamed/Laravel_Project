<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorAppointmentBookingDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->doctor->id,
            'name' => "Dr " . $this->doctor->user->first_name . " " . $this->doctor->user->last_name,
            'average_rating' => $this->doctor->average_rating,
            'years_of_experience' => 10,
            'location' => $this->doctor->location,
            'reviews_count' => $this->doctor->reviews_count,
            'specialization' => $this->doctor->specializations, //->pluck('name')->toArray(),
            'schedules' => $this->doctor->schedules,
            'available_days' => $this->doctor->getAllAvailableDays(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
