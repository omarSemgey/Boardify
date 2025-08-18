<?php

namespace App\Boards\Controllers;

use App\Boards\Requests\StoreBoardRequest;
use App\Boards\Requests\UpdateBoardRequest;
use App\GlobalResources\Resources\BaseApiResource;
use App\Boards\Models\Board;
use App\Boards\Services\BoardCrudService;
use App\Boards\Services\BoardService;
use App\Http\Controllers\Controller;

class BoardController extends Controller
{

    protected BoardCrudService $boardCrudService;
    protected BoardService $boardService;

    public function __construct(BoardCrudService $boardCrudService,BoardService $boardService) {
        $this->boardCrudService = $boardCrudService;
        $this->boardService = $boardService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $boards = $this->boardCrudService->index(10); 

        return BaseApiResource::collection($boards)->withMessage('Boards list.', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBoardRequest $request)
    {
        $createdBoard = $this->boardCrudService->store($request->validated());
            
        return new BaseApiResource($createdBoard)->withMessage('Board created successfully',201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Board $board)
    {
        $board = $this->boardCrudService->show($board);

        return  new BaseApiResource($board)->withMessage('Board:',200);
    }

    //     public function search(Board $board)
    // {
    //     try{
    //         $users = $this->boardCrudService->search($board);
    
    //         if (is_null($users)) {
    //             return response()->json([
    //                 'status' => 'failed', 
    //                 'message' => 'No users exist in this board.',
    //                 'data' => [],
    //             ], 204);
    //         }
    
    //         return BaseApiResource::collection($users)->withMessage('Board users.',200);
    //     }catch (\Throwable $err) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Board user fetch failed. Please try again.'
    //         ], 500);
    //     }
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBoardRequest $request, Board $board)
    {
        $updatedBoard = $this->boardCrudService->update($request->validated(),$board);

        return (new BaseApiResource($updatedBoard))->withMessage('Board updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Board $board)
    {
        $board = $this->boardCrudService->destroy($board);

        return new BaseApiResource($board)->withMessage('Board deleted successfully',200);
    }

    public function getBoardUsers(Board $board){
        $users = $this->boardService->getBoardUsers($board);

        return BaseApiResource::collection($users)->withMessage('Board users.',200);
        }
}