<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // return your admin dashboard view here
        // change the view name to your real admin dashboard blade
        return view('admin.dashboard');
    }

    public function getChartData(Request $request)
    {
        // return chart data as JSON (dummy for now)
        return response()->json([
            'labels' => [],
            'data' => [],
        ]);
    }
}
