<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use App\Services\ShortUrlService;
use App\Support\DateRange;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly ShortUrlService $shortUrlService)
    {
    }

    public function index(Request $request): View
    {
        // dd($request->all());
        $user = $request->user();

        // echo "<pre>";
        // print_r($user);exit;
        $range = DateRange::fromKey($request->string('range')->toString());

        $shortUrls = $this->shortUrlService
            ->visibleShortUrls($user, $range)
            ->paginate(10)
            ->withQueryString();

        $teamMembers = collect();
        $clients = collect();

        if ($user->isSuperAdmin()) {
            $clients = \App\Models\Company::query()
                ->withCount('users')
                ->withSum('shortUrls', 'visits_count')
                ->withCount('shortUrls')
                ->orderBy('name')
                ->paginate(10, ['*'], 'clients_page')
                ->withQueryString();
        }

        if ($user->isCompanyAdmin()) {
            $teamMembers = User::query()
                ->where('company_id', $user->company_id)
                ->withCount('shortUrls')
                ->withSum('shortUrls', 'visits_count')
                ->orderBy('name')
                ->paginate(10, ['*'], 'members_page')
                ->withQueryString();
        }

        $pendingInvitations = Invitation::query()
            ->when($user->isSuperAdmin(), fn ($query) => $query->whereNull('accepted_at'))
            ->when($user->isCompanyAdmin(), fn ($query) => $query->where('company_id', $user->company_id)->whereNull('accepted_at'))
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', ['user' => $user, 'rangeKey' => $request->string('range')->toString(), 'ranges' => DateRange::labels(), 'shortUrls' => $shortUrls, 'teamMembers' => $teamMembers,
            'clients' => $clients, 'pendingInvitations' => $pendingInvitations,]
        );
    }
}