<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class Payment extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Payment');

        return $this->customizeActivitylogOptions();
    }
    protected $guarded = [];

    public function billing()
    {
        return $this->belongsTo(Billing::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(Admin::class);
    }
}
