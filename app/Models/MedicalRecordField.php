<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;

class MedicalRecordField extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Medical Record Field');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'name',
        'short_code',
        'field_type',
        'is_multiple',
        'is_required',
        'order',
        'status',
    ];

    protected $casts = [
        'is_multiple' => 'boolean',
        'is_required' => 'boolean',
        'status' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get the options for this field (for select, multiselect, checkbox types)
     */
    public function options()
    {
        return $this->hasMany(MedicalRecordFieldOption::class)->orderBy('order');
    }

    /**
     * Get all values for this field across all medical records
     */
    public function values()
    {
        return $this->hasMany(MedicalRecordValue::class);
    }

    /**
     * Scope to get only active fields
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope to order by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
