<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class LabTestReferral extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Lab Test Referral');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'lab_test_id', 'referred_clinic_name', 'referred_clinic_address',
        'referred_clinic_contact', 'referral_date', 'status', 'notes'
    ];

    public function laboratoryTest()
    {
        return $this->belongsTo(Test::class, 'lab_test_id');
    }
}
