<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class EmergencyMedicineIssuance extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Emergency Medicine Issuance');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'visit_id',
        'medicine_id',
        'issued_by',
        'quantity',
        'issued_at',
        'reason',
    ];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function issuer()
    {
        return $this->belongsTo(Admin::class, 'issued_by');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($issuance) {
            // Reduce stock from emergency inventory
            $inventory = EmergencyInventory::where('medicine_id', $issuance->medicine_id)->first();

            if ($inventory && $inventory->quantity >= $issuance->quantity) {
                $inventory->decrement('quantity', $issuance->quantity);
            } else {
                throw new \Exception('Not enough stock in emergency inventory');
            }
        });
    }
}
