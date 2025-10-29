<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\NewMenuTestResultCreated;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;

class Billing extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Billing');

        return $this->customizeActivitylogOptions();
    }
    protected static function booted()
    {
        static::created(function ($billing) {
            $message = 'New Payment added for ' . $billing->visit->patient->full_name;
            if ($billing->laboratoryRequest) {
                $message = 'New Laboratory Payment added for ' . $billing->visit->patient->full_name;
            } elseif ($billing->radiologyRequest) {
                $message = 'New Radiology Payment added for ' . $billing->visit->patient->full_name;
            } elseif ($billing->emergencyPrescreption) {
                $message = 'New Inclinic Item Payment added for ' . $billing->visit->patient->full_name;
            } else {
                $message = 'New Payment added for ' . $billing->visit->patient->full_name;
            }

            if (optional(BusinessSetting::where('key', 'is_live')->first())->value) {
                event(new NewMenuTestResultCreated(
                    $message,
                    '/admin/invoice/list',
                    'New Payment',
                    'invoice.list'
                ));
            }
        });

        static::updated(function ($billing) {
            $message = 'Payment updated for ' . $billing->visit->patient->full_name;
            if ($billing->laboratoryRequest) {
                $message = 'Laboratory Payment updated for ' . $billing->visit->patient->full_name;
            } elseif ($billing->radiologyRequest) {
                $message = 'Radiology Payment updated for ' . $billing->visit->patient->full_name;
            } elseif ($billing->emergencyPrescreption) {
                $message = 'Emergency Medicine Payment updated for ' . $billing->visit->patient->full_name;
            } else {
                $message = 'New Payment added for ' . $billing->visit->patient->full_name;
            }

            if (optional(BusinessSetting::where('key', 'is_live')->first())->value) {
                event(new NewMenuTestResultCreated(
                    $message,
                    '/admin/invoice/list',
                    'Payment Updated',
                    'invoice.list'
                ));
            }
        });
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function laboratoryRequest()
    {
        return $this->belongsTo(LaboratoryRequest::class);
    }

    public function radiologyRequest()
    {
        return $this->belongsTo(RadiologyRequest::class);
    }
    public function emergencyPrescreption()
    {
        return $this->belongsTo(EmergencyPrescription::class, 'emergency_medicine_issuance_id');
    }

    public function dischargeService()
    {
        return $this->belongsTo(Discharge::class, 'billing_from_discharge_id');
    }

    public function billingService()
    {
        return $this->belongsTo(BillingService::class, 'billing_service_id');
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function billingDetail()
    {
        return $this->hasMany(BillingDetail::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function canceledByAdmin()
    {
        return $this->belongsTo(Admin::class, 'canceled_by');
    }

    public function patientProcedures()
    {
        return $this->belongsTo(PatientProcedure::class, 'patient_procedures_id');
    }
}
