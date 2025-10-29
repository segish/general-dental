<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class Ward extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Ward');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'ward_name',
        'description',
        'max_beds_capacity',
    ];

    public function beds()
    {
        return $this->hasMany(Bed::class);
    }

    public function ipdRecords()
    {
        return $this->hasMany(IPDRecord::class);
    }
}
