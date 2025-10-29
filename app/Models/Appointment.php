<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class Appointment extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;
    protected $guarded = [];


    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Appointment');

        return $this->customizeActivitylogOptions();
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Admin::class, 'doctor_id');
    }

    public function timeSchedule()
    {
        return $this->belongsTo(TimeSchedule::class);
    }
}
