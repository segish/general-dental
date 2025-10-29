<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;

class Customer extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    protected $fillable = [
        'fullname',
        'email',
        'phone_number',
        'address',
        'status',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Customer');

        return $this->customizeActivitylogOptions();
    }
}
