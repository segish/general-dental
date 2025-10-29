<?php

namespace App\Models;

use App\Traits\ActivityLogTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SocialMedia extends Model
{

    use LogsActivity, ActivityLogTrait;

    protected $casts = [
        'name'        => 'string',
        'link'        => 'string',
        'status'        => 'integer',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

    protected $table = 'social_medias';

        
    public function getActivitylogOptions():LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Social Media');

        return $this->customizeActivitylogOptions();
    }
}
