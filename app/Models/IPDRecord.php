<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class IPDRecord extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('IPD Record');

        return $this->customizeActivitylogOptions();
    }

    protected $table = 'ipd_records';

    protected $fillable = [
        'visit_id',
        'ward_id',
        'bed_id',
        'admitting_doctor_id',
        'admission_date',
        'discharge_date',
        'discharge_summary',
        'ipd_status',
    ];

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
        return $this->belongsTo(Bed::class, 'bed_id');
    }

    public function admittingDoctor()
    {
        return $this->belongsTo(Admin::class, 'admitting_doctor_id');
    }
}
