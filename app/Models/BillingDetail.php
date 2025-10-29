<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;

class BillingDetail extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Billing Detail');

        return $this->customizeActivitylogOptions();
    }
    public function billing()
    {
        return $this->belongsTo(Billing::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function radiology()
    {
        return $this->belongsTo(Radiology::class);
    }

    public function billingService()
    {
        return $this->belongsTo(BillingService::class);
    }

    public function dischargeService()
    {
        return $this->belongsTo(Discharge::class, 'billing_from_discharge_id');
    }

    public function prescreption()
    {
        return $this->belongsTo(EmergencyPrescriptionDetail::class, 'emergency_medicine_issuance_id');
    }
}
