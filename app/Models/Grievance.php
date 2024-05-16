<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Grievance extends Model
{
    use HasFactory , SoftDeletes;
    protected $guarded = '';


    public function hasCreator(){
        return $this->hasOne(User::class, 'id', 'creator_id');
    }

    public function hasUser(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
