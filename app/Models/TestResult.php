<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class TestResult extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Test Result');

        return $this->customizeActivitylogOptions();
    }

    protected $table = 'test_results';

    protected $fillable = [
        'laboratory_request_test_id',
        'result_status',
        'processed_by',
        'process_status',
        'verified_by',
        'verify_status',
        'process_end_time',
        'verify_start_time',
        'verify_end_time',
        'additional_note',
        'comments',
        'image',
    ];

    protected $casts = [
        'process_start_time' => 'datetime',
        'process_end_time' => 'datetime',
        'verify_start_time' => 'datetime',
        'verify_end_time' => 'datetime',
        'image' => 'array', // JSON field
    ];

    // Relationship with LaboratoryRequestTest
    public function laboratoryRequestTest()
    {
        return $this->belongsTo(LaboratoryRequestTest::class);
    }

    // Relationship with Admin for processed_by
    public function processedBy()
    {
        return $this->belongsTo(Admin::class, 'processed_by');
    }

    // Relationship with Admin for verified_by
    public function verifiedBy()
    {
        return $this->belongsTo(Admin::class, 'verified_by');
    }

    // Relationship with TestResultAttribute
    public function attributes()
    {
        return $this->hasMany(TestResultAttribute::class);
    }
}
