<?php

namespace App\Services;

use App\Jobs\SendInvitationEmailJob;
use App\Models\Company;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Mail\InvitationMail;

use Illuminate\Support\Facades\Mail;

class InvitationService
{
    public function create(User $inviter, array $data): Invitation
    {
        $invitation = DB::transaction(function () use ($inviter, $data) {
            $company = null;

            if ($inviter->isSuperAdmin()) {
                $company = Company::firstOrCreate([
                    'name' => $data['company_name'],
                ]);
            } else {
                $company = $inviter->company;
            }

            

            return Invitation::create([
                'company_id' => $company?->id,
                'company_name' => $company?->name ?? $data['company_name'] ?? null,
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'],
                'token' => Str::random(48),
                'invited_by' => $inviter->id,
                'expires_at' => now()->addDays(7),
            ]);
        });

        // SendInvitationEmailJob::dispatch($invitation);

        // return $invitation;
        Mail::to($invitation->email)->send(new InvitationMail($invitation));

        return $invitation;
    }

    public function accept(Invitation $invitation, string $password): User
    {
        abort_if($invitation->isAccepted() || $invitation->isExpired(), 410);

        return DB::transaction(function () use ($invitation, $password) {
            $user = User::create([
                'company_id' => $invitation->company_id,
                'name' => $invitation->name,
                'email' => $invitation->email,
                'role' => $invitation->role,
                'invited_by' => $invitation->invited_by,
                'password' => $password,
                'email_verified_at' => now(),
            ]);

            $invitation->forceFill([
                'accepted_at' => now(),
            ])->save();

            return $user;
        });
    }
}
