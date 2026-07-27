<?php

namespace App\Models;


use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
   
    use HasFactory, Notifiable;

    public const ROLE_SUPER_ADMIN = 'SuperAdmin';
    public const ROLE_ADMIN       = 'Admin';
    public const ROLE_MEMBER      = 'Member';
    public const ROLE_SALES       = 'Sales';
    public const ROLE_MANAGER     = 'Manager';

    public const ROLES = [
        self::ROLE_SUPER_ADMIN,
        self::ROLE_ADMIN,
        self::ROLE_MEMBER,
        self::ROLE_SALES,
        self::ROLE_MANAGER,
    ];


    protected $fillable = [
        'company_id',
        'name',
        'email',
        'role',
        'invited_by',
        'password',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected function casts(){
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function inviter() {
        return $this->belongsTo(self::class, 'invited_by');
    }

    public function invitedUsers()
    {
        return $this->hasMany(self::class, 'invited_by');
    }

    public function invitations(){
        return $this->hasMany(Invitation::class, 'invited_by');
    }

    public function shortUrls()
    {
        return $this->hasMany(ShortUrl::class);
    }

    public function isSuperAdmin(){
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isCompanyAdmin(){
        return $this->role === self::ROLE_ADMIN;
    }

    public function isCompanyCreator() {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_MEMBER, self::ROLE_MANAGER, self::ROLE_SALES], true);
    }

    public function canInviteUsers(){
        return $this->isSuperAdmin() || $this->isCompanyAdmin();
    }

    public function canCreateShortUrls(){
        return $this->company_id !== null
            && in_array($this->role, [self::ROLE_ADMIN, self::ROLE_MEMBER], true);
    }
}
