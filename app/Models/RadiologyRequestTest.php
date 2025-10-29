<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;

class RadiologyRequestTest extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Radiology Request Test');

        return $this->customizeActivitylogOptions();
    }
    protected $table = 'radiology_request_test';
    protected $fillable = ['radiology_request_id', 'radiology_id', 'status', 'additional_note'];

    public function request()
    {
        return $this->belongsTo(RadiologyRequest::class, 'radiology_request_id');
    }

    public function radiology()
    {
        return $this->belongsTo(Radiology::class);
    }

    public function result()
    {
        return $this->hasOne(RadiologyResult::class);
    }
}
