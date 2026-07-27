@extends('layouts.app')

@section('content')
    @component('partials.dashboard-shell', ['user' => $user])
        @if ($user->canCreateShortUrls())
            <div class="block">
                <h2 class="block-title">Generate Short URL</h2>
                <form method="POST" action="{{ route('short-urls.store') }}" class="stack">
                    @csrf
                    <label>
                        Long URL
                        <input type="url" name="original_url" value="{{ old('original_url') }}" placeholder="e.g. https://example.com/very/long/path" required>
                        @error('original_url') <span class="error-text">{{ $message }}</span> @enderror
                    </label>
                    <div>
                        <button type="submit">Generate</button>
                    </div>
                </form>
            </div>
        @endif

        @if ($user->canInviteUsers())
            <div class="block">
                <div class="toolbar">
                    <h2 class="block-title" style="margin-bottom: 0;">Pending Invitations</h2>
                    <form method="GET" action="{{ route('invitations.create') }}">
                        <button type="submit">Invite</button>
                    </form>
                </div>
                <table>
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Company</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($pendingInvitations as $invitation)
                        <tr>
                            <td>{{ $invitation->name }}</td>
                            <td>{{ $invitation->email }}</td>
                            <td>{{ $invitation->role }}</td>
                            <td>{{ $invitation->company_name ?? $invitation->company?->name ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No pending invitations.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        @if ($user->isSuperAdmin())
            <div class="block">
                <div class="toolbar">
                    <h2 class="block-title" style="margin-bottom: 0;">Clients</h2>
                    <form method="GET" action="{{ route('invitations.create') }}">
                        <button type="submit">Invite</button>
                    </form>
                </div>
                <table>
                    <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Users</th>
                        <th>Total Generated URLs</th>
                        <th>Total URL Hits</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($clients as $company)
                        <tr>
                            <td>
                                {{ $company->name }}
                                <div class="meta">{{ $company->users()->value('email') }}</div>
                            </td>
                            <td>{{ $company->users_count }}</td>
                            <td>{{ $company->short_urls_count }}</td>
                            <td>{{ (int) $company->short_urls_sum_visits_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No companies found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="pagination">{{ $clients->links() }}</div>
            </div>
        @endif

        @if ($user->isCompanyAdmin())
            <div class="block">
                <div class="toolbar">
                    <h2 class="block-title" style="margin-bottom: 0;">Team Members</h2>
                    <form method="GET" action="{{ route('invitations.create') }}">
                        <button type="submit">Invite</button>
                    </form>
                </div>
                <table>
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Total Generated URLs</th>
                        <th>Total URL Hits</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($teamMembers as $member)
                        <tr>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->role }}</td>
                            <td>{{ $member->short_urls_count }}</td>
                            <td>{{ (int) $member->short_urls_sum_visits_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No team members found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="pagination">{{ $teamMembers->links() }}</div>
            </div>
        @endif

        <div class="block">
            <div class="toolbar">
                <h2 class="block-title" style="margin-bottom: 0;">Generated Short URLs</h2>
                <div class="inline-form">
                    <form method="GET" action="{{ route('dashboard') }}">
                        <select name="range">
                            <option value="">All Time</option>
                            @foreach ($ranges as $key => $label)
                                <option value="{{ $key }}" @selected($rangeKey === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <button type="submit">View</button>
                    </form>
                    <form method="GET" action="{{ route('short-urls.export') }}">
                        <input type="hidden" name="range" value="{{ $rangeKey }}">
                        <button type="submit">Download</button>
                    </form>
                </div>
            </div>

            <table>
                <thead>
                <tr>
                    <th>Short URL</th>
                    <th>Long URL</th>
                    <th>Hits</th>
                    <th>Creator</th>
                    <th>Company</th>
                    <th>Created On</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($shortUrls as $shortUrl)
                    <tr>
                        <td class="mono"><a href="{{ route('short-urls.show', $shortUrl) }}">{{ route('short-urls.show', $shortUrl) }}</a></td>
                        <td class="mono">{{ $shortUrl->original_url }}</td>
                        <td>{{ $shortUrl->visits_count }}</td>
                        <td>{{ $shortUrl->user->name }}</td>
                        <td>{{ $shortUrl->company->name }}</td>
                        <td>{{ $shortUrl->created_at->format('d M y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No short URLs found for this view.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            <div class="pagination">{{ $shortUrls->links() }}</div>
        </div>
    @endcomponent
@endsection
