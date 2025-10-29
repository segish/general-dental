<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
use App\Models\TestType;
use App\Models\RadiologyType;
use App\Models\RadiologyTestResult;
use App\Models\MedicalLabResult;

class QuickService extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Quick Service');

        return $this->customizeActivitylogOptions();
    }
    protected $guarded = [];

    public function testTypes()
    {
        return $this->belongsToMany(TestType::class);
    }

    public function radiologyTypes()
    {
        return $this->belongsToMany(RadiologyType::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(Admin::class, 'requested_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(Admin::class, 'assigned_to');
    }

    public function radiologyTestResults()
    {
        return $this->hasMany(RadiologyTestResult::class);
    }

    public function medicalLabResults()
    {
        return $this->hasMany(MedicalLabResult::class);
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

}
