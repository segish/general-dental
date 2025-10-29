<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;

class RadiologyRequest extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Radiology Request');

        return $this->customizeActivitylogOptions();
    }
    protected $fillable = [
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
        'additional_note',
        'status'
    ];

    public function radiologies()
    {
        return $this->hasMany(RadiologyRequestTest::class);
    }


    public function radiologyResults()
    {
        return $this->hasMany(RadiologyResult::class);
    }


    public function radiologyResults2()
    {
        return $this->hasManyThrough(RadiologyResult::class, RadiologyRequestTest::class, 'radiology_request_id', 'radiology_request_test_id');
    }


    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function billing()
    {
        return $this->hasOne(Billing::class);
    }

    public function collector()
    {
        return $this->belongsTo(Admin::class, 'collected_by');
    }
}
