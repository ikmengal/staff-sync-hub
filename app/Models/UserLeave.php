<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class UserLeave extends Model
{
    use HasFactory , SoftDeletes;
    protected $guarded = '';

    public function hasEmployee()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function hasLeaveType()
    {
        return $this->hasOne(LeaveType::class, 'id', 'leave_type_id');
    }
}
