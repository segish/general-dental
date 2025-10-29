<?php

namespace App\Models;

use App\Traits\ActivityLogTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class BusinessSetting extends Model
{
    //
    use LogsActivity, ActivityLogTrait;
    protected $guarded = [];

    public function getActivitylogOptions():LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Business Setting');

        return $this->customizeActivitylogOptions();
    }


}
