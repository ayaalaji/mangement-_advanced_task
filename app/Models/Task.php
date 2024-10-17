<?php

namespace App\Models;


use App\Models\Attachement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable =[
        'title',
        'description',
        'type',
        'status',
        'priority',
        'due_date',
        'assigned_to',
    ];
    public static function boot()
    {
        parent::boot();

        //this method is run when status us chanded
        static::updating(function ($task) {
            if ($task->isDirty('status')) { //if the status of task changed do this
                TaskStatusUpdate::create([
                    'task_id' => $task->id,
                    'old_status' => $task->getOriginal('status'), 
                    'new_status' => $task->status, 
                    'updated_by' => auth()->id(), 
                ]);
            }
        });
    }
    public function user()
    {
        $this->belongsTo(User::class ,'assigned_to');
    }
    public function attachements()
    {
        return $this->morphMany(Attachement::class, 'attachable');
    }
    public function statusUpdates()
    {
        return $this->hasMany(TaskStatusUpdate::class);
    }

    public function dependencies()
    {
        return $this->hasMany(Dependency::class, 'task_id');
    }

    public function dependents()
    {
        return $this->hasMany(Dependency::class, 'depends_on_task_id');
    }

    public function isBlocked()
    {
        foreach ($this->dependencies as $dependency) {
            if ($dependency->dependsOn->status !== 'completed') {
                return true;
            }
        }

        return false;
    }

    public function updateTaskStatus()
    {
        if ($this->isBlocked()) {
            $this->update(['status' => 'blocked']);
        } else {
            $this->update(['status' => 'open']); // أو الحالة المناسبة الأخرى
        }
    }


    

}
