<?php

namespace App\Models;

use App\Models\MedicalCondition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;

class MedicalRecord extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Medical Record');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'visit_id',
        'doctor_id',
    ];

    public function doctor()
    {
        return $this->belongsTo(Admin::class);
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function conditions()
    {
        return $this->belongsToMany(MedicalCondition::class, 'medical_record_condition');
    }

    /**
     * Get all field values for this medical record
     */
    public function values()
    {
        return $this->hasMany(MedicalRecordValue::class);
    }

    /**
     * Get a value by field short code
     */
    public function getValueByFieldCode($shortCode)
    {
        $field = MedicalRecordField::where('short_code', $shortCode)->first();
        if (!$field) {
            return null;
        }

        $value = $this->values()->where('medical_record_field_id', $field->id)->first();
        return $value ? $value->decoded_value : null;
    }

    /**
     * Get all values as key-value pairs using short codes
     */
    public function getValuesAsArray()
    {
        $values = [];
        foreach ($this->values as $value) {
            $values[$value->field->short_code] = $value->decoded_value;
        }
        return $values;
    }
}
