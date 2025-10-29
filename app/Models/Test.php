<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class Test extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Test');

        return $this->customizeActivitylogOptions();
    }
    protected $guarded = [];

    public function laboratoryRequests()
    {
        return $this->belongsToMany(LaboratoryRequest::class, 'laboratory_request_test');
    }
    public function testCategory()
    {
        return $this->belongsTo(TestCategory::class);
    }
    public function billingDetails()
    {
        return $this->hasMany(BillingDetail::class);
    }

    // In the TestAttribute model
    public function attributes()
    {
        return $this->hasMany(TestAttribute::class);
    }
    // Relationship with SpecimenType
    public function specimenType()
    {
        return $this->belongsTo(SpecimenType::class, 'specimen_type_id');
    }
}
