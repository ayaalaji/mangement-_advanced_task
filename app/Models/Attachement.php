<?php

namespace App\Models;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attachement extends Model
{
    use HasFactory;
    protected $fillable =[
       'file_path',
       'attachable_id',
       'attachable_type',
       'user_id'
    ];

    public function attachable()
    {
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
