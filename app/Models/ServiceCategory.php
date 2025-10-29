<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;

class ServiceCategory extends Model
{
    protected $table = 'service_categories';

    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Service Category');

        return $this->customizeActivitylogOptions();
    }
    protected $fillable = [
        'name',
        'description',
        'service_type',
    ];

    protected $casts = [
        'service_type' => 'array',
    ];

    /**
     * Accessor to get service_type as an array.
     */
    public function getServiceTypeAttribute($value)
    {
        return $value ? explode(',', $value) : [];
    }

    /**
     * Mutator to set service_type as a comma-separated string.
     */
    public function setServiceTypeAttribute($value)
    {
        $this->attributes['service_type'] = is_array($value)
            ? implode(',', $value)
            : $value;
    }
}
