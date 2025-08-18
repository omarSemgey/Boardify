<?php

namespace App\Boards\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'user_id',
        'visibility',
    ];
}
