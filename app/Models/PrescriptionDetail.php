<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class PrescriptionDetail extends Model
{
        use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Prescription Detail');

        return $this->customizeActivitylogOptions();
    }
    protected $guarded = [];

    public function prescription(){
        return $this->belongsTo(Prescription::class);
    }

    public function medicine(){
        return $this->belongsTo(Medicine::class);
    }
}