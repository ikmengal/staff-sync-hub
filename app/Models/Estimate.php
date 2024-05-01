<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estimate extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = '';

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
    public function requestData()
    {
        return $this->belongsTo(PurchaseRequest::class, 'request_id', 'id');
    }
    public function getStatus()
    {
        return $this->belongsTo(EstimateStatus::class, 'status', 'id');
    }
    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'model_id', 'id')->where("model_name", "\App\Models\Estimate");
    }
}
