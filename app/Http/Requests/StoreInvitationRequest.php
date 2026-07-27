<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvitationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canInviteUsers() ?? false;
    }

    public function rules(): array
    {
        $roles = $this->user()->isSuperAdmin()
            ? [User::ROLE_ADMIN]
            : [User::ROLE_ADMIN, User::ROLE_MEMBER];

        return [
            'company_name' => ['nullable', 'string', 'max:255', Rule::requiredIf($this->user()->isSuperAdmin())],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email', 'unique:invitations,email'],
            'role' => ['required', Rule::in($roles)],
        ];
    }
}
