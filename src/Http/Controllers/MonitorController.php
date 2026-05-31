<?php

namespace BuiltForSmallBusiness\Laravel404Monitor\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use BuiltForSmallBusiness\Laravel404Monitor\Models\FailedRequest;

class MonitorController extends Controller
{
    public function index(Request $request)
    {
        $query = FailedRequest::query();

        if ($search = $request->input('search')) {
            $query->where('url', 'like', '%' . $search . '%');
        }

        if ($source = $request->input('source')) {
            $query->where('source', $source);
        }

        $failedRequests = $query
            ->orderByDesc('hit_count')
            ->paginate(25)
            ->withQueryString();

        $stats = [
            'total_urls'  => FailedRequest::count(),
            'total_hits'  => FailedRequest::sum('hit_count'),
            'googlebot'   => FailedRequest::googlebot()->count(),
            'last_24hrs'  => FailedRequest::recentlyActive(24)->count(),
            'bots'        => FailedRequest::bots()->count(),
            'internal'    => FailedRequest::where('source', 'internal')->count(),
            'external'    => FailedRequest::where('source', 'external')->count(),
        ];

        return view('404monitor::dashboard', compact('failedRequests', 'stats'));
    }

    public function destroy(FailedRequest $failedRequest)
    {
        $failedRequest->delete();

        return back()->with('404monitor_success', 'Entry removed.');
    }

    public function destroyAll()
    {
        FailedRequest::truncate();

        return back()->with('404monitor_success', 'All 404 records cleared.');
    }
}
