<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class PatientProcedure extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Patient Procedure');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'visit_id',
        'doctor_id',
        'billing_service_id',
        'procedure_date',
        'procedure_notes',
    ];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Admin::class, 'doctor_id');
    }

    public function billingService()
    {
        return $this->hasOne(BillingService::class , 'id', 'billing_service_id');
    }
}
