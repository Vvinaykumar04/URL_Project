<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShortUrlRequest;
use App\Models\ShortUrl;
use App\Services\ShortUrlService;
use App\Support\DateRange;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ShortUrlController extends Controller
{
    public function __construct(private readonly ShortUrlService $shortUrlService)
    {
    }

    public function index(Request $request): View
    {
        return app(DashboardController::class)->index($request);
    }

    public function store(StoreShortUrlRequest $request): RedirectResponse
    {
        $this->shortUrlService->create($request->user(), $request->validated()['original_url']);

        return redirect()->route('dashboard')->with('status', 'Short URL generated successfully.');
    }

    public function show(Request $request, ShortUrl $shortUrl): RedirectResponse
    {
        $this->shortUrlService->recordVisit($shortUrl);

        return redirect()->away($shortUrl->original_url);
    }

    public function export(Request $request): Response
    {
        $range = DateRange::fromKey($request->string('range')->toString());
        $rows = $this->shortUrlService->exportRows($request->user(), $range);

        $content = collect($rows)->map(fn (array $row) => implode(',', [
            $row['slug'],
            '"'.str_replace('"', '""', $row['original_url']).'"',
            $row['creator'],
            $row['company'],
            $row['visits'],
            $row['created_at'],
        ]))->prepend('Short URL,Original URL,Creator,Company,Visits,Created At')->implode("\n");

        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="short-urls.csv"',
        ]);
    }
}
