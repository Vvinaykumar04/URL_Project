@php
    $roleKey = \Illuminate\Support\Str::kebab($user->role);

    // echo $roleKey;
    $shellClass = match ($user->role) {
        \App\Models\User::ROLE_SUPER_ADMIN => 'super-admin',
        \App\Models\User::ROLE_ADMIN => 'admin',
        \App\Models\User::ROLE_MEMBER => 'member',
        \App\Models\User::ROLE_MANAGER => 'manager',
        \App\Models\User::ROLE_SALES => 'sales',
        default => 'member',
    };

    // echo "shellClass". $shellClass; 

    
    $kicker = match ($user->role) {
        \App\Models\User::ROLE_SUPER_ADMIN => 'SUPER ADMIN PANEL',
        \App\Models\User::ROLE_ADMIN => 'CLIENT ADMIN DASHBOARD',
        \App\Models\User::ROLE_MEMBER => 'CLIENT MEMBER DASHBOARD',
        \App\Models\User::ROLE_MANAGER => 'CLIENT MANAGER DASHBOARD',
        \App\Models\User::ROLE_SALES => 'CLIENT SALES DASHBOARD',
        default => 'DASHBOARD',
    };
@endphp

<div class="shell {{ $shellClass }}">
    <p class="shell-kicker">{{ $kicker }}</p>
    <div class="panel">
        <div class="topbar">
            <div class="brand">
                <span class="brand-mark">&gt;URL&lt;</span>
                <span>Dashboard</span>
            </div>
            <div class="topbar-actions">
                <span class="meta">{{ $user->name }} · {{ $user->role }}</span>
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-button">Logout →</button>
                </form>
            </div>
        </div>

        <div class="panel-body">
            {{ $slot }}
        </div>
    </div>
</div>
