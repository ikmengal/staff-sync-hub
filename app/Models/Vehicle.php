<?php

namespace App\Models;

use App\Models\VehicleOwner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function hasOwner(){
        return $this->hasOne(VehicleOwner::class, 'id', 'owner_id');
    }
    public function hasRent(){
        return $this->hasOne(VehicleRent::class, 'vehicle_id', 'id')->where('end_date', NULL)->orderby('id', 'desc');
    }
    public function hasImage(){
        return $this->hasOne(VehicleImage::class, 'vehicle_id', 'id');
    }
    public function hasImages(){
        return $this->hasMany(VehicleImage::class, 'vehicle_id', 'id');
    }
}
