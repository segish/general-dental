<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class EmergencyMedicine extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Emergency Medicine');

        return $this->customizeActivitylogOptions();
    }

    protected $table = 'emergency_medicines';

    protected $fillable = [
        'name',
        'description',
        'unit_id',
        'payment_timing',
        'item_type',
        'category_id',
        'low_stock_threshold',
        'expiry_alert_days',
    ];

    /**
     * Relationship: Belongs to an Emergency Medicine
     */
    public function inventory()
    {
        return $this->hasMany(EmergencyInventory::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function category()
    {
        return $this->belongsTo(EmergencyMedicineCategory::class);
    }
}
