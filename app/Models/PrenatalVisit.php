<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class PrenatalVisit extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Prenatal Visit');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'pregnancy_id',
        'visit_id',
        'gestational_age',
        'bp',
        'weight',
        'pallor',
        'uterine_height',
        'fetal_heart_beat',
        'presentation',
        'urine_infection',
        'urine_protein',
        'rapid_syphilis_test',
        'hemoglobin',
        'blood_group_rh',
        'tt_dose',
        'iron_folic_acid',
        'mebendazole',
        'tin_use',
        'arv_px_type',
        'remarks',
        'danger_signs',
        'action_advice_counseling',
        'next_follow_up',
    ];

    // Relationships
    public function pregnancy()
    {
        return $this->belongsTo(Pregnancy::class);
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }
}
