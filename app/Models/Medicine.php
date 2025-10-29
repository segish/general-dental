<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class Medicine extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Medicine');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'code',
        'name',
        'description',
        'category_id',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(MedicineCategory::class, 'category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function prescriptionDetails()
    {
        return $this->hasMany(PrescriptionDetail::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

}
