<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;

class BillingService extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    protected $fillable = [
        'service_name',
        'description',
        'price',
        'billing_type',
        'billing_interval_days',
        'is_active',
        'payment_timing',
        'service_category_id',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Billing Service');

        return $this->customizeActivitylogOptions();
    }
    public function billingDetails()
    {
        return $this->hasMany(BillingDetail::class);
    }

    public function serviceCategory()
    {
        return $this->belongsTo(ServiceCategory::class);
    }
    /**
     * Scope to get only active billing services.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if the service is recurring.
     */
    public function isRecurring()
    {
        return $this->billing_type === 'recurring';
    }
}
