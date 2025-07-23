<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteFeedback;

class SiteFeedbackController extends Controller
{
    public function index()
    {
        return SiteFeedback::with('customer')->paginate();
    }

    public function update(Request $request, SiteFeedback $siteFeedback)
    {
    }
}
