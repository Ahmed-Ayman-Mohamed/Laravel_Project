<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'name',
        'age',
        'message',
        'image',
    ];

    // Relationship with Patient Model
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
