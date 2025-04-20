<?php

namespace AlifAhmmed\Http\Controllers;

use AlifAhmmed\Helpers\MetricsTracker;
use Illuminate\Http\Request;

class MetricsController extends Controller
{
    public function store(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'type' => 'required|in:impression,click',
            'metric_token' => 'required|string',
            'url' => 'nullable|url',
        ]);

        [$type, $id] = MetricsTracker::decodeMetricToken($request->metric_token);

        if (!$type || !$id) {
            return response()->json(['error' => 'Invalid token'], 422);
        }

        MetricsTracker::track($type, $id, $request->url);

        return response()->json(['status' => 'ok']);
    }

    public function getFilteredMetrics(Request $request)
    {
        $request->validate([
            'days' => 'required|in:1,7,14,30,60,90,180,365',
        ]);

        $days = $request->days;

        $metrics = MetricsTracker::getMetricsByDays($days);

        return response()->json([
            'days' => $days,
            'metrics' => $metrics
        ]);
    }

//    public function testCtr()
//    {
//        $ctr = MetricsTracker::calculateCTR(Tester::class, 6, 30);
//
//        return response()->json([
//            'CTR' => $ctr . '%',
//            'trackable_type' => Tester::class,
//            'trackable_id' => 1,
//            'days' => 30,
//        ]);
//    }


}

