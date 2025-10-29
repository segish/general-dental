<?php

namespace App\Models;

use App\Traits\ActivityLogTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OrderDetail extends Model
{

    use LogsActivity, ActivityLogTrait;

    protected $casts = [
        'medicine_id' => 'integer',
        'order_id' => 'integer',
        'price' => 'float',
        'discount_on_product' => 'float',
        'quantity' => 'float',
        'tax_amount' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'customer_id' => 'integer',
    ];

    public function medicine(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Medicine::class, 'medicine_id');
    }

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function inventory()
    {
        return $this->belongsTo(PharmacyInventory::class);
    }
    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Order Detail');

        return $this->customizeActivitylogOptions();
    }
}
