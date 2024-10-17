<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Task;
use App\Models\Attachement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AttachementService;
use App\Http\Requests\Attachement\StoreRequest;
use App\Http\Requests\Attachement\UpdateRequest;

class AttachementController extends Controller
{
    protected $attachementService;
    public function __construct(AttachementService $attachementService)
    {
        $this->middleware('auth:api');
        $this->middleware('security');
        $this->middleware('manager',['except'=>['index']]);
        $this->attachementService = $attachementService;
    }
    /**
     * Display a listing of Attachement.
     * only admin and manager can see attachement
     * @param Task $task
     * @return /Illuminate\Http\JsonResponse
     */
    public function index(Task $task)
    {
        $attachs = $this->attachementService->getAllAttachements($task);
        return $this->success($attachs, 'This is all Attachments');
    }

    /**
     * Store a newly created Attachements.
     * manager can only add attachements
     * @param StoreRequest $request
     * @param Task $task
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request,Task $task)
    {
        $validatedData = $request->validated();

        $attach = $this->attachementService->createAttachement($validatedData, $task);
        if ($attach === false) {
            return $this->error('Cannot add attachment to a task that is blocked.');
        }

        return $this->success($attach, 'You Created Attachment Successfully', 201);
    }


    /**
     * Update the specified  Attachements.
     * manager can only update attachements
     * @param UpdateRequest $request
     * @param Attachement $attach
     * @param Task $task
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Attachement $attach)
    {
        
        
        $validatedData = $request->validated();

        // dd($validatedData);
        $attachUpdate = $this->attachementService->updateAttachement($validatedData, $attach);
        return $this->success($attachUpdate, 'You Updated Attachment Successfully');
    }

    /**
     * Remove the specified Attachements.
     * manager can only remove attachements
     * @param Attachement $attach
     * @return /Illuminate\Http\JsonResponse.
     */
    public function destroy(Attachement $attach)
    {
        $attachDelete = $this->attachementService->deleteAttachemnent($attach);
        if ($attachDelete === false) {
            return $this->error('You are not authorized to delete this attachment.');
        }

        return $this->success(null, 'You Deleted Attachment Successfully');
    }
}
