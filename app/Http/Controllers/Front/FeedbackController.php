<?php

namespace App\Http\Controllers\Front;

use App\Enums\OrderStatus;
use App\Events\RateProductEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\FeedbackRequest;
use App\Models\Feedback;
use App\Models\Product;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function __invoke(FeedbackRequest $request, Product $product)
    {
        $user = auth()->user();

        if (!$product->customerPurchased) {
            return response()->json([
                'message' => 'You cannot rate this product before purchasing it.'
            ], 403);
        }
        $existing = $user->feedbacks()->where('assessable_type', 'product')
            ->where('assessable_id', $product->id)
            ->first();

        if (!$existing) {
            $feedback = $product->feedbacks()->create([
                'user_id' => $user->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'editable_until' => now()->addDays(7),
            ]);
            $feedback->load(['customer:id,first_name,last_name,avatar']);
            event(new RateProductEvent($user, $product, $feedback));
            return response()->json(['message' => 'Feedback submitted successfully.', 'feedback' => $feedback]);
        }

        if ($existing->editable_until && now()->lessThanOrEqualTo($existing->editable_until)) {
            $existing->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
            $existing->load(['customer:id,first_name,last_name,avatar']);
            event(new RateProductEvent($user, $product, $existing));
            return response()->json(['message' => 'Feedback updated successfully.', 'feedback' => $existing]);
        }
        return response()->json([
            'message' => 'You cannot update this feedback anymore.'
        ], 403);
    }
}
