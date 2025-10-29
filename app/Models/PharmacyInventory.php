<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class PharmacyInventory extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Pharmacy Inventory');

        return $this->customizeActivitylogOptions();
    }

    protected $table = 'pharmacy_inventory';

    protected $fillable = [
        'product_id',
        'batch_number',
        'barcode',
        'quantity',
        'buying_price',
        'selling_price',
        'expiry_date',
        'received_date',
        'supplier_id',
        'manufacturer'
    ];

    protected $casts = [
        'buying_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'quantity' => 'float',
        'expiry_date' => 'date',
        'received_date' => 'date'
    ];

    /**
     * Relationship: Belongs to a Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
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
                ->from('products')
                ->whereColumn('id', 'pharmacy_inventory.product_id')
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
        return $this->quantity <= $this->product->low_stock_threshold;
    }

    /**
     * Check if medicine is near expiry
     */
    public function isExpiringSoon()
    {
        return Carbon::parse($this->expiry_date)->diffInDays(Carbon::now()) <= $this->product->expiry_alert_days;
    }
}
