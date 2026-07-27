<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcceptInvitationRequest;
use App\Http\Requests\StoreInvitationRequest;
use App\Models\Invitation;
use App\Services\InvitationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InvitationController extends Controller
{
    public function __construct(private readonly InvitationService $invitationService)
    {
    }

    public function create(Request $request): View
    {
        return view('invitations.create', [
            'user' => $request->user(),
        ]);
    }

    public function store(StoreInvitationRequest $request): RedirectResponse
    {
        $this->invitationService->create($request->user(), $request->validated());

        return redirect()
            ->route('dashboard')
            ->with('status', 'Invitation created successfully.');
    }

    public function acceptForm(string $token): View
    {
        $invitation = Invitation::query()->where('token', $token)->firstOrFail();

        abort_if($invitation->isAccepted() || $invitation->isExpired(), 410);

        return view('invitations.accept', compact('invitation'));
    }

    public function accept(AcceptInvitationRequest $request, string $token): RedirectResponse
    {
        $invitation = Invitation::query()->where('token', $token)->firstOrFail();

        $user = $this->invitationService->accept($invitation, $request->validated()['password']);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }
}
