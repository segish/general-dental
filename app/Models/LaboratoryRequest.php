<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class LaboratoryRequest extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Laboratory Request');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'patient_id',
        'visit_id',
        'referring_dr',
        'referring_institution',
        'card_no',
        'hospital_ward',
        'requested_by',
        'relevant_clinical_data',
        'current_medication',
        'order_status',
        'fasting',
        'collected_by',
        'collected_at',
        'additional_note'
    ];

    protected $guarded = [];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function tests()
    {
        return $this->hasMany(LaboratoryRequestTest::class, 'laboratory_request_id');
    }


    public function testResults()
    {
        return $this->hasMany(TestResult::class);
    }

    public function testResults2()
    {
        return $this->hasManyThrough(TestResult::class, LaboratoryRequestTest::class, 'laboratory_request_id', 'laboratory_request_test_id');
    }

    public function specimens()
    {
        return $this->hasMany(Specimen::class);
    }

    public function billing()
    {
        return $this->hasOne(Billing::class);
    }

    public function billing2()
    {
        return $this->hasOne(Billing::class, 'visit_id', 'visit_id')
            ->whereNotNull('laboratory_request_id');
    }
    
    public function billingMatchingThisRequest()
    {
        return Billing::where('visit_id', $this->visit_id)
            ->whereNotNull('laboratory_request_id')
            ->where('laboratory_request_id', $this->id)
            ->first();
    }

    public function collectedBy()
    {
        return $this->belongsTo(Admin::class, 'collected_by');
    }
}
