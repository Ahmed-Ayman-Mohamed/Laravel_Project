<?php

namespace App\Http\Controllers;

use App\ApiResponseTrait;
use App\Http\Resources\ReviewResource;
use App\Models\Doctor;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    use ApiResponseTrait;
    public function getDoctorReviews($id)
    {
        // Find the doctor
        $doctor = Doctor::findOrFail($id);
        // Get all reviews for the doctor
        $reviews = $doctor->reviews;

        // Get the total number of reviews
        $totalReviews = $reviews->count();

        // Get the average rating (if no reviews, it will return null, so we use default value 0)
        $averageRating = $totalReviews > 0 ? $reviews->avg('rating') : 0;

        // Return reviews, total count, and average rating in response
        return $this->successResponse([
            'total_reviews' => $totalReviews,
            'average_rating' => $averageRating,
            'reviews' => ReviewResource::collection($reviews),
        ]);
    }

    public function store(Request $request, $id)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'review' => 'required|string',
            'rating' => 'required|integer|between:1,5', // Rating should be between 1 and 5
        ]);

        // If validation fails, return errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Assuming the patient is authenticated
        $patientId = $request->user()->patient->id; // Replace this with actual authentication logic
        // Check if the doctor exists
        $doctor = Doctor::findOrFail($id);

        // Create the review
        $review = Review::create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patientId,
            'review' => $request->review,
            'rating' => $request->rating,
        ]);

        return $this->successResponse(new ReviewResource($review), 'Review created successfully', 201);  // Return the created review
    }

    public function update(Request $request, $id)
    {
        // Find the review
        $review = Review::findOrFail($id);
        // Ensure the authenticated user is the same as the patient who wrote the review
        if ($request->user()->patient->id !== $review->patient_id) {
            return $this->unauthorizedResponse('You are not authorized to update this review');
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'review' => 'required|string',
            'rating' => 'required|integer|between:1,5', // Rating should be between 1 and 5
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error', 400, $validator->errors());
        }

        // Update the review
        $review->update([
            'review' => $request->review,
            'rating' => $request->rating,
        ]);

        return $this->successResponse([
            'message' => 'Review updated successfully',
            'review' => new ReviewResource($review),
        ]);
    }

    public function destroy(Request $request, $id)
    {
        // Find the review
        $review = Review::findOrFail($id);

        // Ensure the authenticated user is the same as the patient who wrote the review
        if ($request->user()->patient->id !== $review->patient_id) {
            return $this->unauthorizedResponse('You are not authorized to delete this review');
        }

        // Delete the review
        $review->delete();

        return $this->successResponse(['message' => 'Review deleted successfully']);
    }
}
