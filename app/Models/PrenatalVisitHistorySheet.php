<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class PrenatalVisitHistorySheet extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Prenatal Visit History Sheet');

        return $this->customizeActivitylogOptions();
    }

    protected $table = 'prenatal_visit_history_sheet';

    protected $fillable = [
        'visit_id',
        'history',
        'physical_findings',
        'progress_notes',
        'remarks',
    ];
}
