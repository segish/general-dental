<?php

namespace App\Models;

use App\Models\MedicalCondition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class MedicalRecord extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Medical Record');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'visit_id',
        'doctor_id',
        'chief_complaint',
        'symptoms',
        'medical_history',
        'additional_notes',
    ];

    public function doctor()
    {
        return $this->belongsTo(Admin::class);
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function conditions()
    {
        return $this->belongsToMany(MedicalCondition::class, 'medical_record_condition');
    }
}
