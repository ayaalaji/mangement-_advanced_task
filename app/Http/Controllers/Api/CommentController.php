<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreRequest;
use App\Http\Requests\Comment\UpdateRequest;
use App\Models\Comment;
use App\Models\Task;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentService;
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
        $this->middleware('auth:api');
        $this->middleware('security');
    }

    /**
     * Store a newly created comment by admin and manager and user.
     * @param StoreRequest $request
     * @param Task $task
     * @return /Illuminate\Http\ResponseJson
     */
    public function store(StoreRequest $request,Task $task)
    {
        $validatedData = $request->validated();
        $comment = $this->commentService->addComment($validatedData,$task);
        return $this->success($comment,'You Created Comment Successfully',201);
    }

    /**
     * Update the specified comment in storage.
     * each user can only update his comment.
     * @param UpdateRequest $request
     * @param Comment $comment
     * @return /Illuminate\Http\ResponseJson
     */
    public function update(UpdateRequest $request, Comment $comment)
    {
        $validatedData = $request->validated();
        $commentUpdated = $this->commentService->updateComment($validatedData,$comment);
        return $this->success($commentUpdated,'You Update Comment Successfully');
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return $this->success(null,'You Deleted Comment Successfully');
    }
}
