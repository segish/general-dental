<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class Newborn extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('New born');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'delivery_summary_id',
        'name',
        'para',
        'prom',
        'prom_hours',
        'birth_weight',
        'length',
        'temp',
        'pr',
        'rr',
        'bcg_date',
        'polio_0',
        'vit_k',
        'ttc',
        'term_status',
        'hiv_counts_and_testing_offered',
        'hiv_testing_accepted',
        'apgar_score',
        'sex',
        'length_cm',
        'head_circumference_cm',
        'hiv_test_result',
        'arv_px_mother',
        'arv_px_newborn',
        'baby_mother_bonding',
        'resuscitated',
        'dysmorphic_faces',
        'neonatal_evaluation',
        'plan',
    ];

    // Relationships
    public function deliverySummary()
    {
        return $this->belongsTo(DeliverySummary::class);
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
