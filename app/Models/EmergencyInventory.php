<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;

class EmergencyInventory extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Emergency Inventory');

        return $this->customizeActivitylogOptions();
    }

    protected $table = 'emergency_inventory';

    protected $fillable = [
        'emergency_medicine_id',
        'batch_number',
        'quantity',
        'buying_price',
        'selling_price',
        'expiry_date',
        'received_date',
        'supplier_id',
    ];

    protected $casts = [
        'buying_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'quantity' => 'integer',
        'expiry_date' => 'date',
        'received_date' => 'date'
    ];
    /**
     * Relationship: Belongs to an Emergency Medicine
     */
    public function medicine()
    {
        return $this->belongsTo(EmergencyMedicine::class, 'emergency_medicine_id');
    }

    /**
     * Relationship: Belongs to a Supplier
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Scope: Get items with low stock
     */
    public function scopeLowStock($query)
    {
        return $query->where('quantity', '<=', function ($query) {
            $query->select('low_stock_threshold')
                ->from('emergency_medicines')
                ->whereColumn('id', 'emergency_inventory.emergency_medicine_id')
                ->limit(1);
        });
    }

    /**
     * Scope: Get items near expiry
     */
    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expiry_date', '<=', now()->addDays($days))
            ->where('expiry_date', '>', now());
    }

    /**
     * Check if stock is low
     */
    public function isLowStock()
    {
        return $this->quantity <= $this->medicine->low_stock_threshold;
    }

    /**
     * Check if medicine is near expiry
     */
    public function isExpiringSoon()
    {
        return $this->expiry_date && Carbon::parse($this->expiry_date)->diffInDays(Carbon::now()) <= $this->medicine->expiry_alert_days;
    }
}
