<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = ['doctor_id', 'patient_id', 'treatment_plan_id', 'appointment_date', 'appointment_time', 'status'];

    protected $appends = [
        'day',
    ];
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function treatmentPlan()
    {
        return $this->belongsTo(TreatmentPlan::class);
    }

    public function getDayAttribute()
    {
        return date('l', strtotime($this->appointment_date));
    }
}
