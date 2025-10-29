<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class PatientReport extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Patient Report');

        return $this->customizeActivitylogOptions();
    }
    protected $guarded = [];
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
