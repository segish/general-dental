<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class Pregnancy extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Pregnancy');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'visit_id',
        'patient_id',
        // Core Info
        'lmp',
        'edd',
        'gravida',
        'anc_reg_no',
        'para',
        'children_alive',
        'marital_status',
        'status',
        'is_high_risk',

        // Obstetric History & Risk Factors
        'previous_stillbirth_or_neonatal_loss',
        'spontaneous_abortions_count',
        'last_birth_weight_kg',
        'hypertension_in_last_pregnancy',
        'reproductive_tract_surgery',

        // Current Pregnancy Risk Factors
        'multiple_pregnancy',
        'mother_age',
        'rh_issue',
        'vaginal_bleeding',
        'pelvic_mass',
        'booking_bp_diastolic',
        'diabetes',
        'renal_disease',
        'cardiac_disease',

        // General Medical Conditions
        'chronic_hypertension',
        'substance_abuse',
        'serious_medical_disease',
        'remarks',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function prenatalVisits()
    {
        return $this->hasMany(PrenatalVisit::class);
    }

    public function deliverySummary()
    {
        return $this->hasOne(DeliverySummary::class);
    }

    public function discharges()
    {
        return $this->hasMany(Discharge::class);
    }
}
