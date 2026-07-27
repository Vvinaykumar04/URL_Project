<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortUrlVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_only_see_short_urls_created_in_their_own_company(): void
    {
        $ownCompany = Company::create(['name' => 'Own Company']);
        $otherCompany = Company::create(['name' => 'Other Company']);

        $admin = User::factory()->create([
            'company_id' => $ownCompany->id,
            'role' => User::ROLE_ADMIN,
        ]);

        $ownCompanyCreator = User::factory()->create([
            'company_id' => $ownCompany->id,
            'role' => User::ROLE_MEMBER,
        ]);

        $otherCompanyCreator = User::factory()->create([
            'company_id' => $otherCompany->id,
            'role' => User::ROLE_MEMBER,
        ]);

        $visibleShortUrl = ShortUrl::create([
            'company_id' => $ownCompany->id,
            'user_id' => $ownCompanyCreator->id,
            'original_url' => 'https://own-company.test/visible-url',
            'slug' => 'own123',
        ]);

        $hiddenShortUrl = ShortUrl::create([
            'company_id' => $otherCompany->id,
            'user_id' => $otherCompanyCreator->id,
            'original_url' => 'https://other-company.test/hidden-url',
            'slug' => 'other1',
        ]);

        $response = $this->actingAs($admin)->get(route('short-urls.index'));

        $response->assertOk();
        $response->assertSee($visibleShortUrl->original_url);
        $response->assertDontSee($hiddenShortUrl->original_url);
    }
}
