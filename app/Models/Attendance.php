<?php

namespace App\Models;

use App\Models\AttendanceSummary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory , SoftDeletes;
    protected $guarded = '';


    function attendanceSummary(){
        return $this->hasOne(AttendanceSummary::class,'id','attendance_id');
    }

    
    public function userShift()
    {
        return $this->hasOne(WorkShift::class, 'id', 'work_shift_id');
    }




}
