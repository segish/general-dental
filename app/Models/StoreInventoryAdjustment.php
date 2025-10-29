<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class StoreInventoryAdjustment extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Store Inventory Adjustment');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'store_inventory_id',
        'quantity',
        'adjustment_type',
        'reason',
        'requested_by',
        'approved_by',
        'status',
    ];

    public function storeInventory()
    {
        return $this->belongsTo(StoreInventory::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(Admin::class, 'requested_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }
}
