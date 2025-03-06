<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Mail\MyTestMail;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function register(Request $request, $role)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // Ensure password is confirmed (password_confirmation field must match)
        ]);

        // If validation fails, return a 422 Unprocessable Entity response with the errors
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $role,
        ]);

        // return response()->json(['User' => $user]);

        if ($user->role == 'doctor') {
            $doctor = Doctor::create([
                'user_id' => $user->id,
                'specialization' => $request->specialization,
                'license_number' => $request->license_number,
            ]);
            $user = $doctor->user;
        } else {
            $patient = Patient::create([
                'user_id' => $user->id,
                'description' => $request->description,
            ]);
            $user = $patient->user;
        }

        return response()->json(['message' => 'User Created Successfully'], 201);
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = Auth::guard('api')->attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            // Get the authenticated user.
            $user = Auth::guard('api')->user();

            // Event
            // Mail::to($user->email)->send(new MyTestMail(
            //     $user->first_name,
            //     $user->email,
            //     $user->specialization,
            //     'Welcome Doctor'
            // ));

            return $this->respondWithToken($token, $user);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
    }

    public function logout()
    {
        Auth::invalidate(Auth::getToken());

        return response()->json(['message' => 'Successfully logged out']);
    }

    // Custom method to respond with the token
    protected function respondWithToken($token, $user)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'data' => $user,
            //'expires_in' => auth()->factory()->getTTL() * 60 // Token expiration time in seconds
        ]);
    }
}
