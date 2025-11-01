<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;

class MedicalRecordValue extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Medical Record Value');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'medical_record_id',
        'medical_record_field_id',
        'value',
    ];

    /**
     * Get the medical record this value belongs to
     */
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    /**
     * Get the field definition for this value
     */
    public function field()
    {
        return $this->belongsTo(MedicalRecordField::class, 'medical_record_field_id');
    }

    /**
     * Accessor to decode JSON values
     */
    public function getDecodedValueAttribute()
    {
        $field = $this->field;
        if ($field && in_array($field->field_type, ['multiselect', 'checkbox'])) {
            $decoded = json_decode($this->value, true);
            return is_array($decoded) ? $decoded : [];
        }
        return $this->value;
    }

    /**
     * Mutator to encode JSON values for array types
     */
    public function setValueAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['value'] = json_encode($value);
        } else {
            $this->attributes['value'] = $value;
        }
    }
}
