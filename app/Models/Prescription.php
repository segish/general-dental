<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;

class Prescription extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Prescription');

        return $this->customizeActivitylogOptions();
    }
    protected $guarded = [];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Admin::class);
    }

    public function details()
    {
        return $this->hasMany(PrescriptionDetail::class);
    }
    public function visit()
    {
        return $this->belongsTo(Visit::class, 'visit_id');
    }
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
