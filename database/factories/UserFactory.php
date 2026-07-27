<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => null,
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'role' => User::ROLE_MEMBER,
            'invited_by' => null,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function superAdmin(): static
    {
        return $this->state(fn () => [
            'role' => User::ROLE_SUPER_ADMIN,
            'company_id' => null,
        ]);
    }

    public function admin(?int $companyId = null): static
    {
        return $this->state(fn () => [
            'role' => User::ROLE_ADMIN,
            'company_id' => $companyId,
        ]);
    }

    public function member(?int $companyId = null): static
    {
        return $this->state(fn () => [
            'role' => User::ROLE_MEMBER,
            'company_id' => $companyId,
        ]);
    }

    public function manager(?int $companyId = null): static
    {
        return $this->state(fn () => [
            'role' => User::ROLE_MANAGER,
            'company_id' => $companyId,
        ]);
    }

    public function sales(?int $companyId = null): static
    {
        return $this->state(fn () => [
            'role' => User::ROLE_SALES,
            'company_id' => $companyId,
        ]);
    }
}
