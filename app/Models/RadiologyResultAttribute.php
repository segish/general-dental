<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;

class RadiologyResultAttribute extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Radiology Result Attribute');

        return $this->customizeActivitylogOptions();
    }
    protected $fillable = ['radiology_result_id', 'radiology_attribute_id', 'result_value', 'comments'];

    public function result()
    {
        return $this->belongsTo(RadiologyResult::class, 'radiology_result_id');
    }

    public function attribute()
    {
        return $this->belongsTo(RadiologyAttribute::class, 'radiology_attribute_id');
    }
}
