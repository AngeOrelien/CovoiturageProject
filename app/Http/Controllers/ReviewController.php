<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Booking $booking): JsonResponse
    {
        $user = $request->user();

        // Vérifier que le trajet est terminé
        if ($booking->ride->status !== 'completed') {
            return response()->json(['message' => 'Le trajet doit être terminé pour laisser un avis.'], 422);
        }

        $data = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'type'    => 'required|in:passenger_to_driver,driver_to_passenger',
        ]);

        // Vérifier les droits selon le type d'avis
        $isPassenger = $booking->passenger_id === $user->id;
        $isDriver    = $booking->ride->driver_id === $user->id;

        if ($data['type'] === 'passenger_to_driver' && !$isPassenger) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }
        if ($data['type'] === 'driver_to_passenger' && !$isDriver) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        // Vérifier doublon
        $exists = Review::where('booking_id', $booking->id)
            ->where('reviewer_id', $user->id)
            ->where('type', $data['type'])
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Vous avez déjà laissé un avis pour cette réservation.'], 422);
        }

        $revieweeId = $data['type'] === 'passenger_to_driver'
            ? $booking->ride->driver_id
            : $booking->passenger_id;

        $review = DB::transaction(function () use ($data, $booking, $user, $revieweeId) {
            $review = Review::create([
                'booking_id'  => $booking->id,
                'reviewer_id' => $user->id,
                'reviewee_id' => $revieweeId,
                'rating'      => $data['rating'],
                'comment'     => $data['comment'] ?? null,
                'type'        => $data['type'],
            ]);

            // Recalculer la note moyenne du reviewee
            $this->recalculateRating($revieweeId);

            return $review;
        });

        return response()->json([
            'message' => 'Avis publié avec succès.',
            'review'  => $review->load('reviewer:id,first_name,last_name,avatar_url'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review): JsonResponse
    {
        $revieweeId = $review->reviewee_id;

        $review->delete();

        $this->recalculateRating($revieweeId);

        return response()->json(['message' => 'Avis supprimé.']);
    }

    /**
     * Avis publics d'un utilisateur (en tant que reviewee).
     */
    public function userReviews(User $user): JsonResponse
    {
        $reviews = Review::where('reviewee_id', $user->id)
            ->with('reviewer:id,first_name,last_name,avatar_url', 'booking.ride')
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json([
            'reviews'     => $reviews,
            'rating_avg'  => $user->rating_avg,
            'rating_count'=> $user->rating_count,
        ]);
    }

    /**
     * Recalculer la note moyenne d'un utilisateur.
     */
    private function recalculateRating(string $userId): void
    {
        $stats = Review::where('reviewee_id', $userId)
            ->selectRaw('AVG(rating) as avg, COUNT(*) as count')
            ->first();

        User::where('id', $userId)->update([
            'rating_avg'   => round($stats->avg ?? 0, 2),
            'rating_count' => $stats->count ?? 0,
        ]);
    }
}
