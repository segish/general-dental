<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class TestResultAttribute extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Test Result Attribute');

        return $this->customizeActivitylogOptions();
    }

    protected $table = 'test_result_attributes';

    protected $fillable = [
        'test_result_id',
        'attribute_id',
        'selected_option_value',
        'result_value',
        'comments',
    ];
    protected $casts = [
        'reference_values' => 'array',
    ];
    

    // Relationship with TestResult
    public function testResult()
    {
        return $this->belongsTo(TestResult::class);
    }

    // Relationship with TestAttribute
    public function attribute()
    {
        return $this->belongsTo(TestAttribute::class, 'attribute_id');
    }
}
