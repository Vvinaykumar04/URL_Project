<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberShortUrlVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_only_see_short_urls_created_by_themselves(): void
    {
        $company = Company::create(['name' => 'Own Company']);

        $member = User::factory()->create([
            'company_id' => $company->id,
            'role' => User::ROLE_MEMBER,
        ]);

        $otherMember = User::factory()->create([
            'company_id' => $company->id,
            'role' => User::ROLE_MEMBER,
        ]);

        $visibleShortUrl = ShortUrl::create([
            'company_id' => $company->id,
            'user_id' => $member->id,
            'original_url' => 'https://member.test/my-url',
            'slug' => 'mine01',
        ]);

        $hiddenShortUrl = ShortUrl::create([
            'company_id' => $company->id,
            'user_id' => $otherMember->id,
            'original_url' => 'https://member.test/other-url',
            'slug' => 'other2',
        ]);

        $response = $this->actingAs($member)->get(route('short-urls.index'));

        $response->assertOk();
        $response->assertSee($visibleShortUrl->original_url);
        $response->assertDontSee($hiddenShortUrl->original_url);
    }
}
