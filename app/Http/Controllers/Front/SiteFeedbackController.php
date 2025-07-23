<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\SiteFeedbackRequest;
use Illuminate\Http\Request;
use App\Enums\OrderStatus;
use App\Models\SiteFeedback;
use Illuminate\Http\JsonResponse;

class SiteFeedbackController extends Controller
{
    public function store(SiteFeedbackRequest $request): JsonResponse
    {
        ['can_rate' => $completedOrders, 'already_rated' => $alreadyRated] = $this->checkCanRate($request);
        if ($completedOrders && !$alreadyRated) {
            SiteFeedback::create([
                'customer_id' => $request->user()->id,
                'rating' => $request->validated('rating'),
                'comment' => $request->validated('comment'),
            ]);
            return response()->json(['message' => 'Feedback submitted successfully.']);
        }
        return response()->json(['message' => 'You are not eligible to rate the site.'], 403);

    }
    /**
     * Check if the user is eligible to rate the site.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkEligibility(Request $request): JsonResponse
    {
        ['can_rate' => $completedOrders, 'already_rated' => $alreadyRated] = $this->checkCanRate($request);
        return response()->json([
            'can_rate' => $completedOrders >= 7 && !$alreadyRated,
            'already_rated' => $alreadyRated,
        ]);
    }

    protected function checkCanRate(Request $request)
    {
        $user = $request->user();
        $completedOrders = $user->orders()
            ->where('status', OrderStatus::Completed->value)
            ->count();
        $alreadyRated = SiteFeedback::where('customer_id', $user->id)->exists();
        return [
            'can_rate' => $completedOrders >= 7 && !$alreadyRated,
            'already_rated' => $alreadyRated,
        ];
    }
}
