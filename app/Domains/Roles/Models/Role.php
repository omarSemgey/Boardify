<?php

namespace App\Domains\Roles\Models;

use App\Domains\Boards\Models\Board;
use App\Domains\Permissions\Models\Permission;
use App\Domains\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'type_id',
        'board_id',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
