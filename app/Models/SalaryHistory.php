<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryHistory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function jobHistory()
    {
        return $this->hasOne(JobHistory::class, 'id', 'job_history_id');
    }
    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function hasUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
