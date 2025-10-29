<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;

class Radiology extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Radiology');

        return $this->customizeActivitylogOptions();
    }
    protected $fillable = [
        'radiology_name',
        'title',
        'description',
        'additional_notes',
        'cost',
        'time_taken_hour',
        'time_taken_min',
        'paper_size',
        'is_inhouse',
        'paper_orientation',
        'is_active'
    ];

    public function attributes()
    {
        return $this->hasMany(RadiologyAttribute::class);
    }
    public function billingDetails()
    {
        return $this->hasMany(BillingDetail::class);
    }

    public function requestTests()
    {
        return $this->hasMany(RadiologyRequestTest::class);
    }
}
