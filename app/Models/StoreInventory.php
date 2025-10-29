<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class StoreInventory extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Store Inventory');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'item_id',
        'batch_number',
        'quantity',
        'buying_price',
        'selling_price',
        'received_date',
        'expiry_date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
