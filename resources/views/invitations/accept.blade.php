@extends('layouts.app')

@section('content')
    <div class="shell admin" style="max-width: 760px; margin: 48px auto 0;">
        <p class="shell-kicker">INVITATION</p>
        <div class="panel">
            <div class="topbar">
                <div class="brand">
                    <span class="brand-mark">&gt;URL&lt;</span>
                    <span>Accept Invitation</span>
                </div>
            </div>

            <div class="panel-body">
                <div class="block" style="max-width: 520px;">
                    <h2 class="block-title">Create Your Account</h2>
                    <p class="meta">You are joining <strong>{{ $invitation->company_name }}</strong> as <strong>{{ $invitation->role }}</strong>.</p>
                    <form method="POST" action="{{ route('invitations.store', $invitation->token) }}" class="stack" style="margin-top: 14px;">
                        @csrf
                        <label>
                            Password
                            <input type="password" name="password" required>
                            @error('password') <span class="error-text">{{ $message }}</span> @enderror
                        </label>

                        <label>
                            Confirm Password
                            <input type="password" name="password_confirmation" required>
                        </label>

                        <div>
                            <button type="submit">Create Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
