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
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = Auth::attempt($credentials)) {
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

            if($user->role === 'patient'){
                $user = User::with('patient')->find($user->id);
            }else{
                $user = User::with('doctor')->find($user->id);
            }

            return $this->respondWithToken($token, $user);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
    }

    public function me(Request $request)
    {
        $user = $request->user();
        // $doctor = $user->doctor;
        return response()->json([
            'doctor' => $user,
        ]);
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
