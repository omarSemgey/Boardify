<?php

namespace App\Domains\Users\Models;

use App\Domains\Boards\Models\Board;
use App\Domains\Roles\Models\Role;
use App\Domains\Users\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile',
    ];

    protected $hidden = [
        'password',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function boards()
    {
        return $this->belongsToMany(Board::class, 'board_user');
    }

        protected static function newFactory()
    {
        return UserFactory::new();
    }

        public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
