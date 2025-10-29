<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class PharmacyStockAdjustment extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Pharmacy Stock Adjustment');

        return $this->customizeActivitylogOptions();
    }

    protected $table = 'pharmacy_stock_adjustments';

    protected $fillable = [
        'medicine_id',
        'pharmacy_inventory_id',
        'quantity',
        'adjustment_type',
        'reason',
        'requested_by',
        'approved_by',
        'status',
    ];

    /**
     * Get the medicine associated with the stock adjustment.
     */
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    /**
     * Get the inventory record associated with the stock adjustment.
     */
    public function pharmacyInventory()
    {
        return $this->belongsTo(PharmacyInventory::class);
    }

    /**
     * Get the admin who requested the adjustment.
     */
    public function requestedBy()
    {
        return $this->belongsTo(Admin::class, 'requested_by');
    }

    /**
     * Get the admin who approved/rejected the adjustment.
     */
    public function approvedBy()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    /**
     * Scope for pending adjustments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    /**
     * Scope for approved adjustments.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'Approved');
    }

    /**
     * Scope for rejected adjustments.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'Rejected');
    }
}
