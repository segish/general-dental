<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class TestAttribute extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Test Attribute');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'test_id',
        'attribute_name',
        'attribute_type',
        'test_category',
        'has_options',
        'lower_limit',
        'upper_limit',
        'unit_id',
        'lower_operator',
        'upper_operator',
        'reference_text',
        'default_required',
        'index',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function options()
    {
        return $this->hasMany(AttributeOption::class, 'attribute_id');
    }

    // public function tests()
    // {
    //     return $this->belongsToMany(Test::class, 'test_required_attributes')
    //                 ->withPivot('is_required');
    // }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function attributeReferences()
    {
        return $this->hasMany(TestAttributeReference::class);
    }
}
