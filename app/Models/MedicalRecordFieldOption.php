<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;

class MedicalRecordFieldOption extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Medical Record Field Option');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'medical_record_field_id',
        'option_value',
        'option_label',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Get the field this option belongs to
     */
    public function field()
    {
        return $this->belongsTo(MedicalRecordField::class, 'medical_record_field_id');
    }

    /**
     * Scope to order by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
