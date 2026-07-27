<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicShortUrlRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_short_urls_are_publicly_resolvable_and_redirect_to_the_original_url(): void
    {
        $company = Company::create(['name' => 'Public Company']);

        $creator = User::factory()->create([
            'company_id' => $company->id,
            'role' => User::ROLE_MEMBER,
        ]);

        $shortUrl = ShortUrl::create([
            'company_id' => $company->id,
            'user_id' => $creator->id,
            'original_url' => 'https://example.com/original-destination',
            'slug' => 'public1',
            'visits_count' => 0,
        ]);

        $response = $this->get(route('short-urls.show', $shortUrl));

        $response->assertRedirect('https://example.com/original-destination');

        $this->assertDatabaseHas('short_urls', [
            'id' => $shortUrl->id,
            'visits_count' => 1,
        ]);

        $this->assertNotNull($shortUrl->fresh()->last_visited_at);
    }
}
