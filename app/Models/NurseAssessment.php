<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class NurseAssessment extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Nurse Assessment');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'patient_id',
        'nurse_id',
        'visit_id',
        'category_id',
        'test_name',
        'test_value',
        'unit_name',
        'notes',
        'recorded_at',
    ];

    public function category()
    {
        return $this->belongsTo(AssessmentCategory::class, 'category_id');
    }

    public function nurse()
    {
        return $this->belongsTo(Admin::class, 'nurse_id');
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class, 'visit_id');
    }
}
