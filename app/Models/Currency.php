<?php

namespace App\Models;

use App\Traits\ActivityLogTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Currency extends Model
{
    use LogsActivity, ActivityLogTrait;

    protected $fillable = [
        'country','currency_code', 'currency_symbol', 'exchange_rate'
    ];

        
    public function getActivitylogOptions():LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Currency');

        return $this->customizeActivitylogOptions();
    }
}
