<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }
    public function departmentBridge()
    {
        return $this->hasOne(DepartmentUser::class, 'user_id', 'id')->orderby('id', 'desc');
    }
    public function userWorkingShift()
    {
        return $this->hasOne(WorkingShiftUser::class, 'user_id', 'id')->orderby('id', 'desc');
    }
    public function departmentBridgeTerminate()
    {
        return $this->hasOne(DepartmentUser::class, 'user_id', 'id')->orderby('id', 'desc');
    }
    public function jobHistory()
    {
        return $this->hasOne(JobHistory::class, 'user_id', 'id')->orderby('id', 'desc');
    }
    public function salaryHistory()
    {
        return $this->hasOne(SalaryHistory::class, 'user_id')->orderby('id', 'desc');
    }
    public function employeeStatusEndDateNull()
    {
        return $this->hasOne(UserEmploymentStatus::class, 'user_id', 'id')->where('end_date', null)->orderby('id', 'desc');
    }

    public function hasResignation(){
        return $this->hasOne(Resignation::class, 'employee_id', 'id')->orderby('id', 'desc');
    }
}
