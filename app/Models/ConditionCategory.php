<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;

class ConditionCategory extends Model
{
    use LogsActivity, ActivityLogTrait;
    protected $fillable = ['name', 'type', 'description'];

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Condition Category');

        return $this->customizeActivitylogOptions();
    }
    public function conditions()
    {
        return $this->hasMany(MedicalCondition::class, 'category_id');
    }
}
