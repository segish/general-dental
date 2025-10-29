<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class LaboratoryRequestTest extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Laboratory Request Test');

        return $this->customizeActivitylogOptions();
    }

    protected $table = 'laboratory_request_test';

    protected $fillable = [
        'laboratory_request_id',
        'test_id',
        'status',
        'additional_note',
    ];

    // Relationship with LaboratoryRequest
    public function laboratoryRequest()
    {
        return $this->belongsTo(LaboratoryRequest::class);
    }

    // Relationship with Test
    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    // Relationship with TestResult
    public function result()
    {
        return $this->hasOne(TestResult::class, 'laboratory_request_test_id');
    }

    public function specimens()
    {
        return $this->belongsToMany(Specimen::class, 'specimen_laboratory_request_test');
    }
}
