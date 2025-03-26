<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;

class Doctor extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use Notifiable;
    use HasFactory;
    protected $table = 'doctors';

    protected $fillable = [
        'user_id',
        'specialization',
        'degree',
        'university',
        'year_graduated',
        'location',
        'license_number',
        'price',
        'cv_file',
    ];

    protected $appends = [
        'reviews_count',
        'average_rating',
    ];

    protected $hidden = ['user_id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function specializations()
    {
        return $this->belongsToMany(Specialization::class, 'pivot_specializations');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function treatmentPlans()
    {
        return $this->hasMany(TreatmentPlan::class);
    }

    public function getAverageRatingAttribute()
    {
        return round($this->reviews()->avg('rating'), 2);
    }

    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public static function filter($filters)
    {
        $query = self::query();

        if (isset($filters['review_rating'])) {
            $query->whereHas('reviews', function ($query) use ($filters) {
                $query->selectRaw('AVG(rating) as average_rating')
                    ->groupBy('doctor_id')
                    ->having('average_rating', '>=', $filters['review_rating']);
            });
        }

        // Filter by minimum price
        if (isset($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        // Filter by maximum price
        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        return $query;
    }

    public function getAllAvailableDays()
    {
        // Get current date
        $currentDate = date('Y-m-d');

        // Get the doctor's available working days from schedule
        $schedules = Schedule::where('doctor_id', $this->id)
            ->pluck('available_days')
            ->toArray();

        if (empty($schedules)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Doctor has no available schedule.'
            ], 404);
        }

        $schedules = array_map('strtolower', $schedules); // Ensure lowercase for comparison

        $availableDays = [];
        $daysChecked = 0;

        while (count($availableDays) < 10) { // Keep checking until we get 10 available days
            $date = date('Y-m-d', strtotime("+$daysChecked days", strtotime($currentDate))); // Get next days
            $dayName = strtolower(date('l', strtotime($date))); // Get day name

            // Check if this day is in the doctor's schedule
            if (in_array($dayName, $schedules)) {
                $availableDays[] = [
                    'date' => $date,
                    'day' => ucfirst($dayName)
                ];
            }

            $daysChecked++; // Move to the next day
        }

        return $availableDays;
    }
}
