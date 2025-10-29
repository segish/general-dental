<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class DeliverySummary extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    protected $fillable = [
        'pregnancy_id',
        'visit_id',
        'delivered_by',
        'date',
        'time',
        'delivery_mode',
        'placenta',
        'cct',
        'mrp',
        'laceration_repair',
        'laceration_degree',
        'amstl',
        'misoprostol',
        'episiotomy',
        'newborn_type',
        'apgar_score',
        'delivery_outcome',
        'stillbirth_type',
        'obstetric_complication',
        'obstetric_management_status',
        'ruptured_uterus_repaired',
        'hysterectomy',
        'feeding_option',
        'referred_for_support',
        'remarks',
    ];

        public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Delivery Summary');

        return $this->customizeActivitylogOptions();
    }
    // Relationships
    public function pregnancy()
    {
        return $this->belongsTo(Pregnancy::class);
    }

    public function newborns()
    {
        return $this->hasMany(Newborn::class);
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function deliveredBy()
    {
        return $this->belongsTo(Admin::class, 'delivered_by');
    }
}
