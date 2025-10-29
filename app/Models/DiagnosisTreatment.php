<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class DiagnosisTreatment extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    protected $fillable = [
        'visit_id',
        'doctor_id',
        'diagnosis',
        'treatment',
        'additional_notes',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Diagnosis Treatment');

        return $this->customizeActivitylogOptions();
    }
    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function diseases()
    {
        return $this->belongsToMany(MedicalCondition::class, 'diagnosis_disease', 'diagnosis_treatment_id', 'medical_condition_id');
    }
}
