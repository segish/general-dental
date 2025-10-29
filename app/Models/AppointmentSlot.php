<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TimeSchedule;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;

class AppointmentSlot extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Appointment Slot');

        return $this->customizeActivitylogOptions();
    }
    public function timeSchedule()
    {
        return $this->belongsTo(TimeSchedule::class);
    }
}
