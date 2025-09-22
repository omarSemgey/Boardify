<?php

namespace App\Domains\Boards\Services;

use App\GlobalExceptions\ApiException;
use App\Domains\Boards\Models\Board;
use Auth;
use Illuminate\Support\Str;

class BoardCrudService
{
    public function index(int $perPage = 10)
    {
        try {
            $boards = Board::orderBy('created_at', 'desc')
                ->paginate($perPage);

            return $boards;

        } catch (\Throwable $err) {
            throw new ApiException('Fetching boards failed. Please try again later.', 500, $err);
        }
    }

    public function store(array $data)
    {
        try{
            $board = Board::create([
                'uuid' => (string) Str::uuid(),
                'name' => $data['name'],
                'description' => $data['description'],
                'user_id' => Auth::id(),
                'visibility' => $data['visibility'],
            ]);

            return $board;
        }catch (\Throwable $err) {
            throw new ApiException('Board creation failed. Please try again later.', 500, $err);
        }
    }

    public function update(array $data,Board $board)
    {
        try {
            $updateData = collect($data)->toArray();

            $board->fill($updateData);

            if ($board->isDirty()) {
                $board->save();
            }

            return $board;
        } catch (\Throwable $err) {
            throw new ApiException('Board update failed. Please try again later.', 500, $err);
        }
    }

    public function show(Board $board){
        try{
            $board->load(['user', 'notes']); 

            return $board;
    
        }catch (\Throwable $err) {
            throw new ApiException('Fetching board failed. Please try again later.', 500, $err);
        }
    }
 
    public function destroy(Board $board)
    {
        try{

            $board->delete();

            return $board;

        } catch (\Throwable $err) {
            throw new ApiException('Board deletion failed. Please try again later.', 500, $err);
        }
    }
}