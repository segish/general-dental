<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class EmergencyMedicineCategory extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Emergency Medicine Category');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'name',
        'description',
    ];

    public function medicines()
    {
        return $this->hasMany(EmergencyMedicine::class, 'category_id');
    }
}
