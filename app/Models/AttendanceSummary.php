<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSummary extends Model
{
    use HasFactory;

    protected $guarded = [];

    function hasUser(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    function attendance(){
        return $this->belongsTo(Attendance::class,'attendance_id','id');
    }

    public function userShift()
    {
        return $this->hasOne(WorkShift::class, 'id', 'user_shift_id');
    }
}
