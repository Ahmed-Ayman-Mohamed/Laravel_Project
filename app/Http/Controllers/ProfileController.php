<?php

namespace App\Http\Controllers;

use App\Http\Resources\DoctorResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function updateDoctorProfile(Request $request)
    {
        $user = $request->user();
        // Validate incoming data
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'phone' => 'sometimes|string|max:15|unique:users,phone,' . $user->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update user info
        $user->update($request->only(['first_name', 'last_name', 'email', 'phone']));
        $user->refresh();
        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'doctor' => new DoctorResource($user),
        ]);
    }
}
