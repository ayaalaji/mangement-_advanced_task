<?php

namespace App\Services;

use Exception;
use App\Models\Task;
use App\Models\User;
use App\Models\Attachement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AttachementService {
    /**
     * admin and manager and user can see all attachement.
     * but manager appear the tasks which manager add attachement to it
     * and user can see only task he worked it and have an attachement
     * @param Task $task
     */
    public function getAllAttachements(Task $task)
    {
        try {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return Attachement::all();
            //appear the tasks which manager add attachement to it        
            } elseif ($user->role === 'manager') {
                return Attachement::where('user_id', $user->id)
                    ->get();
                    
            //User sees attachments only if the task is assigned to them        
            } elseif ($user->role === 'user') {
                return Attachement::where('attachable_type', Task::class)
                    ->where('attachable_id', $task->id)
                    ->whereHas('attachable', function ($query) {
                        $query->where('assigned_to', Auth::user()->id);
                    })->get();
            } else {
                throw new Exception('You cannot see the Attachments.');
            }
        } catch (Exception $th) {
            Log::error($th);
            throw new Exception('You cannot see the Attachments.');
        }
    }
    /**
     * create an attachement by manager
     * @param array $data
     * @param Task $task
     */
    public function createAttachement(array $data,Task $task)
    {
        try {
            $user = Auth::user();
            $file_path = $data['file_path'];
            $fileName = time() . '.' . $file_path->getClientOriginalExtension();
            $file_path->move(public_path('attachments'), $fileName);

            if($user->manages_type == $task->type && $task->status !='blocked') 
            {

                $attach =  Attachement::create([
                    'file_path' => 'attachments/' . $fileName,
                    'attachable_id' => $task->id, 
                    'attachable_type' => Task::class, 
                    'user_id' => $user->id
                ]);
                
                
               $attach->attachable()->associate($task);
               $attach->save();
    
                return $attach;
            } else {
                return false;
            }
        } catch (Exception $th) {
            Log::error($th);
            throw new Exception('You cannot add the Attachments.');
        }
    }
    /**
     * update specifice  attachement by manager.
     * @param array $data
     * @param Attachement $attach
     */

    public function updateAttachement(array $data, Attachement $attach)
    {
        try {
        // dd($attach);
        // Log::info('Updating attachment', ['data' => $data, 'attachment' => $attach]);
            $updateData = array_filter([
                'file_path' => isset($data['file_path']) ? $this->uploadFile($data['file_path']) : $attach->file_path,
            ]);
        

            // Log::info('Update Data', ['updateData' => $updateData]);
            $attach->update($updateData);
            // dd($attach);

            return $attach; 
        } catch (\Throwable $th) {
            Log::error($th);
            throw new Exception('You cannot update the Attachments.');
        }
    }

    protected function uploadFile($file)
    {
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('attachments'), $fileName);
    
        return 'attachments/' . $fileName;
    }
    /**
     * delete specifice attachement by manager
     * @param Attachement $attach
     */
    public function deleteAttachemnent(Attachement $attach)
    {
        try {
            $user = Auth::user();

            $attachedUser = User::find($attach->user_id);
        Log::info('Attachment owner', ['owner' => $attachedUser]);
            // Check if manager who deletes the attachment is the same manager who added this attachment
            if ($user->role !== 'manager' || $attach->user_id != $user->id) {
                return false; 
            }
            $attach->delete();
            return true;
        } catch (\Throwable $th) {
            Log::error($th);
            throw new Exception('You cannot delete the Attachments.');
        }
    }
    
}