<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class Product extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Product');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'medicine_id',
        'name',
        'product_code',
        'image',
        'unit_id',
        'tax',
        'discount',
        'discount_type',
        'low_stock_threshold',
        'expiry_alert_days'
    ];

    protected $casts = [
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'low_stock_threshold' => 'integer',
        'expiry_alert_days' => 'integer'
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function pharmacyInventories()
    {
        return $this->hasMany(PharmacyInventory::class);
    }

    public function getStockAttribute()
    {
        return $this->pharmacyInventories()->sum('quantity');
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('/storage/app/public/product/' . $this->image) : null;
    }
}
