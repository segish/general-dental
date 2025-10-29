<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;

class EmergencyPrescriptionDetail extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

        public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Emergency Prescription Detail');

        return $this->customizeActivitylogOptions();
    }
    protected $guarded = [];

    public function prescription()
    {
        return $this->belongsTo(EmergencyPrescription::class, 'emergency_prescription_id');
    }

    public function medicine()
    {
        return $this->belongsTo(EmergencyInventory::class, 'emergency_inventory_id');
    }

    public function issuedBy()
    {
        return $this->belongsTo(Admin::class, 'issued_by');
    }
}
