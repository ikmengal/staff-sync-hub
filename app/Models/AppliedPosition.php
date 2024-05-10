<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class AppliedPosition extends Model
{
    use HasFactory ;
    protected $guarded = '';
    
    public function hasPosition()
    {
        return $this->hasOne(Position::class, 'id', 'applied_for_position');
    }


}
