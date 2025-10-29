<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\CauserResolver;
use App\Traits\ActivityLogTrait;

class Admin extends Authenticatable
{
    use HasRoles, LogsActivity, ActivityLogTrait;

    protected $guarded = [];

    protected static $logAttributes = ['*'];
    protected static $logName = 'admin';
    protected static $submitEmptyLogs = false;

    protected function getDefaultGuardName(): string
    {
        return 'admin';
    }

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('User');

        return $this->customizeActivitylogOptions();
    }

    public function timeSchedules()
    {
        return $this->hasMany(TimeSchedule::class, 'doctor_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function billings()
    {
        return $this->hasMany(Billing::class);
    }

    // Relationship with Specimen (as Checker)
    public function checkingSpecimens()
    {
        return $this->hasMany(Specimen::class, 'checker_id');
    }

    // Relationship with Specimen (as Approver)
    public function approvedSpecimens()
    {
        return $this->hasMany(Specimen::class, 'approved_by');
    }

    public function getFullNameAttribute()
    {
        return $this->f_name . ' ' . $this->l_name;
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class, 'received_by_id');
    }

    public function getSignatureUrlAttribute()
    {
        if ($this->signature) {
            return asset('storage/' . $this->signature);
        }
        return null;
    }
}
