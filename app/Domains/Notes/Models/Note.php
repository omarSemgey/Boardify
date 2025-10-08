<?php

namespace App\Domains\Notes\Models;

use App\Global\GlobalContracts\HasBoardId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model implements HasBoardId
{
    /** @use HasFactory<\Database\Factories\NoteFactory> */
    use HasFactory;

   public function getBoardId(): int
    {
        return $this->board_id;
    }
}
