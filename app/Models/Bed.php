<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
use Carbon\Carbon;

class Bed extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    protected $fillable = [
        'bed_number',
        'status',
        'price',
        'type',
        'ward_id',
        'room_number',
        'occupancy_status',
        'additional_notes',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Bed');

        return $this->customizeActivitylogOptions();
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function ipdRecord()
    {
        return $this->hasOne(IPDRecord::class);
    }

    public function calculateStayDays($bedId)
    {
        $bed = Bed::find($bedId);

        if ($bed && $bed->admission_date) {
            $admissionDate = $bed->admission_date;
            $currentDate = Carbon::now();

            $stayDays = $currentDate->diffInDays($admissionDate);

            return $stayDays;
        }

        return 0;
    }

    public function notes()
    {
        return $this->hasMany(PatientNote::class);
    }
}
