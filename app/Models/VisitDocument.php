<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;

class VisitDocument extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    protected $fillable = [
        'visit_id',
        'document_path',
        'original_name',
        'file_type',
        'mime_type',
        'file_size',
        'note',
        'uploaded_by'
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Visit Document');

        return $this->customizeActivitylogOptions();
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(Admin::class, 'uploaded_by');
    }

    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getFileIconAttribute()
    {
        switch ($this->file_type) {
            case 'image':
                return 'tio-image';
            case 'pdf':
                return 'tio-pdf';
            case 'document':
                return 'tio-file';
            default:
                return 'tio-file';
        }
    }
}
