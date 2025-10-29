<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class OpdRecord extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Opd Record');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'visit_id',
    ];

    /**
     * Get the visit associated with the OPD record.
     */
    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }
}
