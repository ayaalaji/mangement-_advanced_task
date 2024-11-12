<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Task;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentService {
    /**
     * add new comment by admin/manager/user 
     * @param array $data
     * @param Task $task
     */ 
    public function addComment(array $data ,Task $task)
    {
        try{
            return Comment::create([
                'content' =>$data['content'],
                'user_id' => Auth::user()->id,
                'commentable_id' => $task->id, 
                'commentable_id_type' => Task::class,
            ]);
        } catch(Exception $e) {
            Log::info($e->getMessage());
            throw new Exception('Something went wrong');
        }
    }
    /**
     * update comment 
     * each user can only update his comment
     * @param array $data
     * @param Comment $comment
     */
    public function updateComment(array $data ,Comment $comment)
    {
        try{
            if($comment->user_id == Auth::id()){
                $updatedComment = array_filter([
                    'content' =>$data['content'] ?? $comment->content,
                ]);
                $comment->update($updatedComment);
                return $comment;
            } else {
                return false;
            }
                
        }catch(Exception $e) {
            Log::info($e->getMessage());
            throw new Exception('Something went wrong. ');
        }
    }
    
}