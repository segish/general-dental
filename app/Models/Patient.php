<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;

class Patient extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;
    protected $guarded = [];

    protected $appends = ['age'];

    protected $casts = [
        'date_of_birth' => 'date', // Cast dob as Carbon instance
    ];

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Patient');

        return $this->customizeActivitylogOptions();
    }

    public function pregnancies()
    {
        return $this->hasMany(Pregnancy::class);
    }

    public function getImageUrl()
    {
        return asset('/storage/' . $this->image);
    }

    public function billings()
    {
        return $this->hasManyThrough(Billing::class, Visit::class);
    }


    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    public function getAgeDetailedAttribute()
    {
        $dob = $this->date_of_birth;
        $now = now();
        $diff = $dob->diff($now);
    
        return "{$diff->y} years {$diff->m} months";
    }
    public function newborns()
    {
        return $this->hasMany(Newborn::class);
    }

    public function children()
    {
        return $this->hasMany(Patient::class, 'mother_id');
    }

    public function mother()
    {
        return $this->belongsTo(Patient::class, 'mother_id');
    }
}
