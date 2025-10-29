<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class Item extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Item');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'name',
        'description',
        'is_sellable',
    ];

    public function storeInventory()
    {
        return $this->hasMany(StoreInventory::class);
    }

    public function usageRequests()
    {
        return $this->hasMany(StoreUsageRequest::class);
    }
}
