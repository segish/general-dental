<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class DiagnosisDisease extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;


    // Specify the table name if it's not the plural form of the model name
    protected $table = 'diagnosis_disease';

    // Disable auto-incrementing if you have a custom ID
    public $incrementing = true;

    // If you're using timestamps
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Diagnosis Disease');

        return $this->customizeActivitylogOptions();
    }
    // Define the relationships
    public function diagnosisTreatment()
    {
        return $this->belongsTo(DiagnosisTreatment::class, 'diagnosis_treatment_id');
    }

    public function medicalCondition()
    {
        return $this->belongsTo(MedicalCondition::class, 'medical_condition_id');
    }

    public function diagnoses()
    {
        return $this->belongsToMany(DiagnosisTreatment::class, 'diagnosis_disease', 'medical_condition_id', 'diagnosis_treatment_id');
    }
}
