<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Monitor</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            font-size: 14px;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .header {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header h1 {
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .header h1 span {
            font-size: 18px;
        }

        .header-sub {
            font-size: 12px;
            color: #64748b;
            margin-top: 2px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 12px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
        }

        .stat-label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 6px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 600;
        }

        .stat-card.googlebot .stat-value {
            color: #dc2626;
        }

        .stat-card.recent .stat-value {
            color: #d97706;
        }

        .toolbar {
            display: flex;
            gap: 12px;
            margin-bottom: 16px;
            align-items: center;
            flex-wrap: wrap;
        }

        .toolbar form {
            display: flex;
            gap: 8px;
            flex: 1;
            min-width: 200px;
        }

        .toolbar input {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 13px;
            outline: none;
            transition: border-color .15s;
        }

        .toolbar input:focus {
            border-color: #6366f1;
        }

        .toolbar select {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 13px;
            background: #fff;
            outline: none;
        }

        .btn {
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all .15s;
        }

        .btn-primary {
            background: #6366f1;
            color: #fff;
            border-color: #6366f1;
        }

        .btn-primary:hover {
            background: #4f46e5;
        }

        .btn-danger {
            background: #fff;
            color: #dc2626;
            border-color: #fca5a5;
        }

        .btn-danger:hover {
            background: #fef2f2;
        }

        .btn-ghost {
            background: transparent;
            color: #64748b;
            border-color: #e2e8f0;
        }

        .btn-ghost:hover {
            background: #f1f5f9;
        }

        .btn-sm {
            padding: 4px 10px;
            font-size: 12px;
        }

        .card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 10px 16px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        td {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: #f8fafc;
        }

        .url-cell {
            font-family: monospace;
            font-size: 13px;
            color: #4f46e5;
            max-width: 400px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .hit-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            color: #475569;
            font-weight: 600;
            font-size: 12px;
            border-radius: 20px;
            padding: 2px 10px;
            min-width: 36px;
        }

        .hit-badge.high {
            background: #fef2f2;
            color: #dc2626;
        }

        .hit-badge.medium {
            background: #fffbeb;
            color: #d97706;
        }

        .source-badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 500;
            padding: 2px 8px;
            border-radius: 20px;
            text-transform: capitalize;
        }

        .source-bot {
            background: #fef2f2;
            color: #dc2626;
        }

        .source-internal {
            background: #eff6ff;
            color: #2563eb;
        }

        .source-external {
            background: #fffbeb;
            color: #d97706;
        }

        .source-direct {
            background: #f1f5f9;
            color: #475569;
        }

        .googlebot-flag {
            display: inline-block;
            font-size: 10px;
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fca5a5;
            border-radius: 4px;
            padding: 1px 5px;
            margin-left: 4px;
            font-weight: 600;
        }

        .empty {
            text-align: center;
            padding: 48px 24px;
            color: #94a3b8;
        }

        .empty-icon {
            font-size: 40px;
            margin-bottom: 12px;
        }

        .empty p {
            font-size: 15px;
            font-weight: 500;
            color: #64748b;
            margin-bottom: 4px;
        }

        .empty span {
            font-size: 13px;
        }

        .pagination {
            display: flex;
            gap: 4px;
            justify-content: center;
            padding: 16px;
        }

        .pagination a,
        .pagination span {
            padding: 6px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 13px;
            color: #64748b;
        }

        .pagination a:hover {
            background: #f1f5f9;
        }

        .pagination .active span {
            background: #6366f1;
            color: #fff;
            border-color: #6366f1;
        }

        .alert {
            padding: 10px 16px;
            border-radius: 6px;
            margin-bottom: 16px;
            font-size: 13px;
        }

        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .last-seen {
            font-size: 12px;
            color: #94a3b8;
        }

        .delete-form {
            display: inline;
        }
    </style>
</head>

<body>

    <div class="header">
        <div>
            <h1><span><i class="fa-solid fa-magnifying-glass"></i></span> 404 Monitor</h1>
            <div class="header-sub">Pages returning 404 on your application</div>
        </div>
        <a href="{{ url('/') }}" class="btn btn-ghost btn-sm">← Back to app</a>
    </div>

    <div class="container">

        @if (session('404monitor_success'))
            <div class="alert alert-success">{{ session('404monitor_success') }}</div>
        @endif

        {{-- Stats --}}
        <div class="stats">
            <div class="stat-card">
                <div class="stat-label">Unique URLs</div>
                <div class="stat-value">{{ number_format($stats['total_urls']) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Hits</div>
                <div class="stat-value">{{ number_format($stats['total_hits']) }}</div>
            </div>
            <div class="stat-card googlebot">
                <div class="stat-label">Googlebot Hits</div>
                <div class="stat-value">{{ number_format($stats['googlebot']) }}</div>
            </div>
            <div class="stat-card recent">
                <div class="stat-label">Last 24 Hours</div>
                <div class="stat-value">{{ number_format($stats['last_24hrs']) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Bots Total</div>
                <div class="stat-value">{{ number_format($stats['bots']) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Internal Links</div>
                <div class="stat-value">{{ number_format($stats['internal']) }}</div>
            </div>
        </div>

        {{-- Toolbar --}}
        <div class="toolbar">
            <form method="GET" action="{{ route('404monitor.index') }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search URLs…">
                <select name="source" onchange="this.form.submit()">
                    <option value="">All sources</option>
                    <option value="bot" @selected(request('source') === 'bot')>Bots</option>
                    <option value="internal" @selected(request('source') === 'internal')>Internal</option>
                    <option value="external" @selected(request('source') === 'external')>External</option>
                    <option value="direct" @selected(request('source') === 'direct')>Direct</option>
                </select>
                <button type="submit" class="btn btn-primary">Search</button>
                @if (request('search') || request('source'))
                    <a href="{{ route('404monitor.index') }}" class="btn btn-ghost">Clear</a>
                @endif
            </form>

            @if ($stats['total_urls'] > 0)
                <form method="POST" action="{{ route('404monitor.destroy-all') }}" class="delete-form"
                    onsubmit="return confirm('Clear all 404 records? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Clear All</button>
                </form>
            @endif
        </div>

        {{-- Table --}}
        <div class="card">
            @if ($failedRequests->isEmpty())
                <div class="empty">
                    <div class="empty-icon"><i class="fa-solid fa-circle-check"></i></div>
                    <p>No 404s recorded yet</p>
                    <span>Requests returning 404 will appear here automatically</span>
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>URL</th>
                            <th>Source</th>
                            <th>Hits</th>
                            <th>Last Seen</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($failedRequests as $request)
                            <tr>
                                <td>
                                    <span class="url-cell" title="/{{ $request->url }}">/{{ $request->url }}</span>
                                    @if ($request->isGooglebot())
                                        <span class="googlebot-flag">Googlebot</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="source-badge source-{{ $request->source }}">
                                        {{ $request->source }}
                                    </span>
                                </td>
                                <td>
                                    <span
                                        class="hit-badge {{ $request->hit_count >= 50 ? 'high' : ($request->hit_count >= 10 ? 'medium' : '') }}">
                                        {{ number_format($request->hit_count) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="last-seen">
                                        {{ $request->last_seen_at?->diffForHumans() ?? '—' }}
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('404monitor.destroy', $request) }}"
                                        class="delete-form" onsubmit="return confirm('Remove this entry?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-sm">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if ($failedRequests->hasPages())
                    <div class="pagination">
                        {{ $failedRequests->links() }}
                    </div>
                @endif
            @endif
        </div>

        <p style="text-align:center; margin-top: 20px; font-size: 12px; color: #94a3b8;">
            laravel-404-monitor by <a href="https://builtforsmallbusiness.com" style="color:#6366f1;">Built For Small
                Business</a>
        </p>

    </div>
</body>

</html>
