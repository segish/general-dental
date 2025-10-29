<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class Discharge extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Discharge');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'visit_id',
        'stay_days',
        'admission_date',
        'discharge_date',
        'discharge_type',
        'discharge_notes',
        'remarks',
        'attending_physician',
    ];

    // Relationships
    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function bed()
    {
        return $this->belongsTo(Bed::class);
    }

    public function physician()
    {
        return $this->belongsTo(Admin::class, 'attending_physician');
    }
}
