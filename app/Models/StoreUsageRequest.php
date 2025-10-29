<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class StoreUsageRequest extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Billing Service');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'item_id',
        'requested_by',
        'quantity',
        'request_date',
        'notes',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function requester()
    {
        return $this->belongsTo(Admin::class, 'requested_by');
    }
}
