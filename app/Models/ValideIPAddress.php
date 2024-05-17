<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ValideIPAddress extends Model
{
    use HasFactory , SoftDeletes;
    protected $guarded = '';


    public function creator(){
        return $this->hasOne(User::class, 'id', 'creator_id');
    }

}
