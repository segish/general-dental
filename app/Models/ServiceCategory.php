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

    /**
     * Accessor to get service_type as an array.
     */
    public function getServiceTypeAttribute($value)
    {
        if (empty($value)) {
            return [];
        }
        return is_string($value) ? explode(',', $value) : (is_array($value) ? $value : []);
    }

    /**
     * Mutator to set service_type as a comma-separated string.
     */
    public function setServiceTypeAttribute($value)
    {
        if (is_array($value)) {
            // Filter out empty values and implode
            $filtered = array_filter($value);
            $this->attributes['service_type'] = !empty($filtered) ? implode(',', $filtered) : null;
        } else {
            $this->attributes['service_type'] = $value;
        }
    }
}
