<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class Unit extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Unit');

        return $this->customizeActivitylogOptions();
    }

    // Define the table associated with the model
    protected $table = 'units';

    // Define the fillable columns to allow mass assignment
    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    // Optionally, you can define relationships if needed in future
}
