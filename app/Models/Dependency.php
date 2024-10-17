<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependency extends Model
{
    use HasFactory;

    protected $table = 'task_dependencies';
    protected $fillable = [
        'task_id',
        'depends_on_task_id'
    ];

    //relation with task
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    //relation for the task who depend on another task
    public function dependsOn()
    {
        return $this->belongsTo(Task::class, 'depends_on_task_id');
    }
}
