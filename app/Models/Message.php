<?php

namespace App\Models;

use App\Traits\ActivityLogTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Message extends Model
{

   use LogsActivity, ActivityLogTrait;

    protected $casts = [
        'conversation_id' => 'integer',
        'customer_id' => 'integer',
        'deliveryman_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function getActivitylogOptions():LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Message');

        return $this->customizeActivitylogOptions();
    }      
    
}
