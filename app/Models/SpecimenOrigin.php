<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class SpecimenOrigin extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Specimen Origin');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'name',
        'description'
    ];

    // Relationship with Specimen
    public function specimens()
    {
        return $this->hasMany(Specimen::class);
    }
}
