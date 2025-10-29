<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;

class AssessmentCategory extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    protected $fillable = [
        'category_type',
        'name',
        'unit_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Assessment Category');

        return $this->customizeActivitylogOptions();
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function nurseAssessments()
    {
        return $this->hasMany(NurseAssessment::class, 'category_id');
    }

    public function labourFollowup()
    {
        return $this->hasMany(LabourFollowup::class, 'category_id');
    }
}
