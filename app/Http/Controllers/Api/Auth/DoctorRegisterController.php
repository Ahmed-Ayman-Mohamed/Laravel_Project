<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorRegisterRequest;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\ApiResponseTrait; // Import the trait

class DoctorRegisterController extends Controller
{
    use ApiResponseTrait;

    public function register(DoctorRegisterRequest $request)
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'doctor',
        ]);

        // if ($request->hasFile('cv_file')) {
        //     $cvFilePath = $request->file('cv_file')->store('cv_files', 'public'); // Stores in storage/app/public/cv_files
        // } else {
        //     $cvFilePath = null;
        // }

        Doctor::create([
            'user_id' => $user->id,
            'degree' => $request->degree,
            'university' => $request->university,
            'year_graduated' => $request->year_graduated,
            'location' => $request->location,
            // 'cv_file' => null, // Save file path in the database
        ]);

        $doctor = User::with('doctor')->find($user->id);

        return $this->successResponse($doctor, 'Doctor registered successfully', 201);
    }
}
