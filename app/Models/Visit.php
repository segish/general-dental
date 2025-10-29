<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ActivityLogTrait;

class Visit extends Model
{
    use HasFactory, LogsActivity, ActivityLogTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth('admin')->user();

        $this->activityLogCauser = $user;
        $this->setActivityLogUserSuffix('Visit');

        return $this->customizeActivitylogOptions();
    }

    protected $fillable = ['patient_id', 'doctor_id', 'code', 'appointment_id', 'visit_type', 'visit_datetime', 'service_category_id', 'additional_notes'];

    protected $casts = [
        'visit_datetime' => 'datetime',
        'admission_date' => 'datetime',
    ];
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }


    public function serviceCategory()
    {
        return $this->belongsTo(ServiceCategory::class);
    }
    public function doctor()
    {
        return $this->belongsTo(Admin::class, 'doctor_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }

    public function deliverySummary()
    {
        return $this->hasOne(DeliverySummary::class);
    }

    public function discharge()
    {
        return $this->hasOne(Discharge::class);
    }

    public function diagnosisTreatment()
    {
        return $this->hasOne(DiagnosisTreatment::class);
    }

    public function ipdRecord()
    {
        return $this->hasOne(IPDRecord::class);
    }

    public function opdRecord()
    {
        return $this->hasOne(OPDRecord::class);
    }

    public function billings()
    {
        return $this->hasMany(Billing::class);
    }

    public function laboratoryRequest()
    {
        return $this->hasOne(LaboratoryRequest::class);
    }

    public function pregnancy()
    {
        return $this->hasOne(Pregnancy::class);
    }

    public function prenatalVisit()
    {
        return $this->hasOne(PrenatalVisit::class);
    }

    public function prenatalVisitHistory()
    {
        return $this->hasOne(PrenatalVisitHistorySheet::class);
    }
    public function radiologyRequest()
    {
        return $this->hasOne(RadiologyRequest::class);
    }

    public function nurseAssessments()
    {
        return $this->hasMany(NurseAssessment::class);
    }

    public function labourFollowups()
    {
        return $this->hasMany(LabourFollowup::class);
    }

    public function prescription()
    {
        return $this->hasMany(Prescription::class);
    }

    public function documents()
    {
        return $this->hasMany(MedicalDocument::class);
    }

    public function visitDocuments()
    {
        return $this->hasMany(VisitDocument::class);
    }

    public function emergencyPrescriptions()
    {
        return $this->hasMany(EmergencyPrescription::class);
    }

    public function getFormattedVisitDateAttribute()
    {
        return \Carbon\Carbon::parse($this->visit_datetime)->format('M d, Y h:i A');
    }

    public function procedures()
    {
        return $this->hasMany(PatientProcedure::class);
    }

    public function dentalCharts()
    {
        return $this->hasMany(DentalChart::class);
    }

    // public function labReferrals()
    // {
    //     return $this->hasMany(LabReferral::class);
    // }
}
