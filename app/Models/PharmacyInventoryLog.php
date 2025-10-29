<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;

class PharmacyInventoryLog extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Pharmacy Inventory Log');

        return $this->customizeActivitylogOptions();
    }
    protected $fillable = [
        'product_id',
        'inventory_id',
        'seller_id',
        'buyer_id',
        'buyer_type',
        'action',
        'quantity',
        'balance_after',
        'reference',
        'date',
        'remarks',
    ];

    // 🔗 Relationships

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function inventory()
    {
        return $this->belongsTo(PharmacyInventory::class);
    }

    public function seller()
    {
        return $this->belongsTo(Admin::class, 'seller_id');
    }

    public function buyer()
    {
        return $this->belongsTo(Customer::class, 'buyer_id');
    }

    /**
     * Scope: Get logs for a specific medicine
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope: Get logs for a specific action (in/out)
     */
    public function scopeForAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope: Get logs for a specific seller
     */
    public function scopeForSeller($query, $sellerId)
    {
        return $query->where('seller_id', $sellerId);
    }

    /**
     * Scope: Get logs for a specific date range
     */
    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }
}
