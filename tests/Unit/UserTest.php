<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_admin_and_member_can_create_short_urls_when_they_belong_to_a_company(): void
    {
        $admin = new User([
            'role' => User::ROLE_ADMIN,
            'company_id' => 1,
        ]);

        $member = new User([
            'role' => User::ROLE_MEMBER,
            'company_id' => 1,
        ]);

        $this->assertTrue($admin->canCreateShortUrls());
        $this->assertTrue($member->canCreateShortUrls());
    }

    public function test_super_admin_cannot_create_short_urls(): void
    {
        $superAdmin = new User([
            'role' => User::ROLE_SUPER_ADMIN,
            'company_id' => null,
        ]);

        $this->assertFalse($superAdmin->canCreateShortUrls());
    }
}
