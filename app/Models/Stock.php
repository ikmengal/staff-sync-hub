<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Stock extends Model
{
    use HasFactory , SoftDeletes;
    protected $guarded = '';

    public function hasImages(){
        return $this->hasMany(StockImage::class, 'stock_id', 'id');
    }

    public function hasUser(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function hasCompany(){
        return $this->hasOne(Company::class, 'id', 'company_id');
    }
}