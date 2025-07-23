<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteFeedback;

class CustomerFeedbackController extends Controller
{
    public function index(Request $request)
    {
        return SiteFeedback::with(['customer:id,email,first_name,last_name,avatar'])->filter(json_decode($request->filter))->paginate();
    }

    public function update(SiteFeedback $feedback)
    {
        $feedback->update([
            'is_featured' => !$feedback->is_featured,
        ]);

        return response()->json([
            'message' => 'Feedback status updated successfully',
            'feedback' => $feedback->load('customer:id,email,first_name,last_name,avatar'),
        ]);
    }
}
