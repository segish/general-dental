<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;

class Specimen extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Specimen');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'specimen_code',
        'checker_id',
        'specimen_type_id',
        'specimen_origin_id',
        'laboratory_request_id',
        'status',
        'notes',
        'checking_start_time',
        'checking_end_time',
        'origin_type',
        'specimen_taken_at'
    ];

    protected $casts = [
        'specimen_code' => 'string',
    ];


    // Relationship with Admin (Checker)
    public function adminChecker()
    {
        return $this->belongsTo(Admin::class, 'checker_id');
    }

    // Relationship with Admin (Approver)
    public function approvedBy()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    // Relationship with SpecimenOrigin
    public function specimenOrigin()
    {
        return $this->belongsTo(SpecimenOrigin::class, 'specimen_origin_id');
    }

    // Relationship with TestRequest
    public function laboratoryRequest()
    {
        return $this->belongsTo(LaboratoryRequest::class);
    }

    public function laboratoryRequestTests()
    {
        return $this->belongsToMany(LaboratoryRequestTest::class, 'specimen_laboratory_request_test');
    }
}
