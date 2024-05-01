<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseRequest extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = '';

    public function company()
    {
        return $this->belongsTo(Company::class , 'company_id' , 'company_id');
    }
    public function getStatus() {
        return $this->belongsTo(PurchaseRequestStatus::class ,'status' , 'id');
    }
    public function modifiedBy() {
        return $this->belongsTo(User::class ,'modified_by' , 'id');
    }
}
