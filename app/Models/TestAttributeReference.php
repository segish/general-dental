<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class TestAttributeReference extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Test Attribute Reference');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'test_attribute_id',
        'gender',
        'min_age',
        'max_age',
        'is_pregnant',
        'lower_limit',
        'upper_limit',
        'lower_operator',
        'upper_operator',
        'reference_text',
        'is_default',
    ];

    public function testAttribute()
    {
        return $this->belongsTo(TestAttribute::class);
    }
}
