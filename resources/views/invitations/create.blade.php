@extends('layouts.app')

@section('content')
    @component('partials.dashboard-shell', ['user' => $user])
        <div class="block">
            <h2 class="block-title">
                {{ $user->isSuperAdmin() ? 'Invite New Client Admin' : 'Invite New Team Member' }}
            </h2>
            <form method="POST" action="{{ route('invitations.send') }}" class="stack">
                @csrf
                <div class="field-grid">
                    @if ($user->isSuperAdmin())
                        <label>
                            Company Name
                            <input type="text" name="company_name" value="{{ old('company_name') }}" placeholder="Client Name..." required>
                            @error('company_name') <span class="error-text">{{ $message }}</span> @enderror
                        </label>
                    @endif

                    <label>
                        Name
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="User Name" required>
                        @error('name') <span class="error-text">{{ $message }}</span> @enderror
                    </label>

                    <label>
                        Email
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="ex. sample@example.com" required>
                        @error('email') <span class="error-text">{{ $message }}</span> @enderror
                    </label>

                    <label>
                        Role
                        <select name="role" required>
                            @if ($user->isSuperAdmin())
                                <option value="Admin">Admin</option>
                            @else
                                <option value="Admin">Admin</option>
                                <option value="Member">Member</option>
                            @endif
                        </select>
                        @error('role') <span class="error-text">{{ $message }}</span> @enderror
                    </label>
                </div>

                <div>
                    <button type="submit">Send Invitation</button>
                </div>
            </form>
        </div>
    @endcomponent
@endsection
