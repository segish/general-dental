<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;
class ProcedureItemUsage extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Procedure ItemU sage');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = [
        'patient_procedure_id',
        'item_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    public function patientProcedure()
    {
        return $this->belongsTo(PatientProcedure::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Calculate subtotal automatically if unit_price is set
    public function calculateSubtotal()
    {
        return $this->quantity * $this->unit_price;
    }
}
