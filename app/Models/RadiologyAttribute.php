<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;

class RadiologyAttribute extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Radiology Attribute');

        return $this->customizeActivitylogOptions();
    }
    protected $fillable = ['radiology_id', 'attribute_name', 'result_type', 'default_required', 'template'];

    public function radiology()
    {
        return $this->belongsTo(Radiology::class);
    }
}
