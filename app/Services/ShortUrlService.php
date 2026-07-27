<?php

namespace App\Services;

use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ShortUrlService
{
    public function create(User $user, string $originalUrl): ShortUrl
    {
        abort_unless($user->canCreateShortUrls(), 403);

        return ShortUrl::create([
            'company_id' => $user->company_id,
            'user_id' => $user->id,
            'original_url' => $originalUrl,
            'slug' => $this->generateUniqueSlug(),
        ]);
    }

    public function recordVisit(ShortUrl $shortUrl): void
    {
        $shortUrl->increment('visits_count');
        $shortUrl->forceFill([
            'last_visited_at' => now(),
        ])->save();
    }

    public function exportRows(User $user, ?array $range): array
    {
        return $this->visibleShortUrls($user, $range)
            ->get()
            ->map(fn (ShortUrl $shortUrl) => [
                'slug' => route('short-urls.show', $shortUrl),
                'original_url' => $shortUrl->original_url,
                'creator' => $shortUrl->user->name,
                'company' => $shortUrl->company->name,
                'visits' => $shortUrl->visits_count,
                'created_at' => $shortUrl->created_at->toDateTimeString(),
            ])
            ->all();
    }

    public function visibleShortUrls(User $user, ?array $range = null)
    {
        return ShortUrl::query()
            ->with(['company', 'user'])
            ->when($range !== null, fn ($query) => $query->whereBetween('created_at', $range))
            ->when($user->isSuperAdmin(), fn ($query) => $query)
            ->when($user->isCompanyAdmin(), fn ($query) => $query->where('company_id', $user->company_id))
            ->when($user->role === User::ROLE_MEMBER, fn ($query) => $query->where('user_id', $user->id))
            ->when(
                $user->isCompanyCreator() && ! $user->isCompanyAdmin() && $user->role !== User::ROLE_MEMBER,
                fn ($query) => $query->where('company_id', $user->company_id)
            )
            ->latest();
    }

    private function generateUniqueSlug(): string
    {
        do {
            $slug = Str::lower(Str::random(6));
        } while (ShortUrl::query()->where('slug', $slug)->exists());

        return $slug;
    }
}
