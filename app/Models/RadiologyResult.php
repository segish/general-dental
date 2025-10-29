<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;

class RadiologyResult extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Radiology Result');

        return $this->customizeActivitylogOptions();
    }
    protected $fillable = [
        'radiology_request_test_id',
        'result_status',
        'processed_by',
        'process_status',
        'verified_by',
        'verify_status',
        'process_end_time',
        'verify_start_time',
        'verify_end_time',
        'additional_note',
        'comments',
        'image'
    ];

    public function requestTest()
    {
        return $this->belongsTo(RadiologyRequestTest::class, 'radiology_request_test_id');
    }

    public function processedBy()
    {
        return $this->belongsTo(Admin::class, 'processed_by');
    }

    public function radiologyRequestTest()
    {
        return $this->belongsTo(RadiologyRequestTest::class, 'radiology_request_test_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(Admin::class, 'verified_by');
    }

    public function attributes()
    {
        return $this->hasMany(RadiologyResultAttribute::class);
    }
}
