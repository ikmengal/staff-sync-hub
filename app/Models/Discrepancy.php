<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Discrepancy extends Model
{
    use HasFactory;
    protected $guarded = '';

    public function hasAttendance()
    {
        return $this->hasOne(Attendance::class,'id', 'attendance_id');
    }

    public function hasEmployee()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
