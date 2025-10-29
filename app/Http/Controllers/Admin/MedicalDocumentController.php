<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalDocument;
use App\Models\Patient;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\View\View;
use App\Models\Admin;
use App\Models\Visit;

class MedicalDocumentController extends Controller
{
    function __construct(
        private MedicalDocument $medicalDocument,

    ) {
        $this->middleware('checkAdminPermission:medical_document.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:medical_document.add-new,index')->only(['index']);
    }

    public function index()
    {
        // Fetch all medical certifications
        $patients = Patient::all();
        $doctors = Admin::all();
        $visits = Visit::all();
        return view('admin-views.medical-document.index', compact('patients', 'doctors', 'visits'));
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        $date = $request['date'];

        $query = $this->medicalDocument
            ->with(['visit.patient', 'visit.doctor']) // Adjust relationship names if different
            ->when($search, function ($q) use ($search) {
                $key = explode(' ', $search);
                $q->where(function ($q2) use ($key) {
                    foreach ($key as $value) {
                        $q2->orWhere('type', 'like', "%{$value}%")
                            ->orWhere('language', 'like', "%{$value}%")
                            ->orWhere('notes', 'like', "%{$value}%")
                            ->orWhere('date', 'like', "%{$value}%")
                            ->orWhereHas('visit.patient', function ($subQ) use ($value) {
                                $subQ->where('full_name', 'like', "%{$value}%")
                                    ->orWhere('registration_no', 'like', "%{$value}%");
                            })
                            ->orWhereHas('visit.doctor', function ($subQ) use ($value) {
                                $subQ->where('f_name', 'like', "%{$value}%");
                            });
                    }
                });
            })
            ->when($date, function ($q) use ($date) {
                $q->whereDate('created_at', '=', $date);
            })
            ->latest();

        $query_param = ['search' => $search, 'date' => $date];

        $medicalDocuments = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.medical-document.list', compact('medicalDocuments', 'search', 'date'));
    }

    public function store(Request $request)
    {
        // Common validation rules for all form types
        $commonRules = [
            'type' => 'required|in:abortion,consent,certification,examination,referal,circumcision,police_certificate',
            'visit_id' => 'required|exists:visits,id',
            'date' => 'required|date',
            'language' => 'required|in:amharic,english',
            'notes' => 'nullable|string',
        ];

        // Type-specific validation rules
        $typeSpecificRules = [
            'consent' => [
                'witness_1_name' => 'required|string|max:255',
                'witness_1_relationship' => 'required|string|max:255',
                'witness_2_name' => 'required|string|max:255',
                'witness_2_relationship' => 'required|string|max:255',
            ],
            'certification' => [
                'diagnosis' => 'required|string|max:255',
                'date_of_rest' => 'required|numeric',
            ],
            'examination' => [
                'to' => 'required|string|max:255',
                'number' => 'required|string|max:255',
                // Patient Self Declaration
                'past_diseases' => 'nullable|string',
                'hospitalization_history' => 'nullable|string',
                'self_declaration_verified' => 'nullable|string|max:255',
                'patient_signature' => 'nullable|string|max:255',
                'patient_signature_date' => 'nullable|date',
                // Doctor's Examination
                'general_appearance' => 'nullable|string',
                'visual_acuity_od' => 'nullable|string|max:255',
                'visual_acuity_os' => 'nullable|string|max:255',
                'hearing_test' => 'nullable|string|max:255',
                'lung_examination' => 'nullable|string',
                'lung_xray' => 'nullable|string|max:255',
                'heart_condition' => 'nullable|string',
                'blood_pressure' => 'nullable|string|max:255',
                'pulse' => 'nullable|string|max:255',
                'abdomen_examination' => 'nullable|string',
                'gut_examination' => 'nullable|string',
                'musculoskeletal_examination' => 'nullable|string',
                'mental_status' => 'nullable|string|max:255',
                'nervous_system_symptoms' => 'nullable|string|max:255',
                // Laboratory Examination
                'hiv_result' => 'nullable|string|max:255',
                'syphilis_result' => 'nullable|string|max:255',
                'hbsag_result' => 'nullable|string|max:255',
                'wbc_result' => 'nullable|string|max:255',
                'hcv_result' => 'nullable|string|max:255',
                'esr_result' => 'nullable|string|max:255',
                'blood_group' => 'nullable|string|max:255',
                'pregnancy_test' => 'nullable|string|max:255',
                // Final Statement
                'final_medical_status' => 'nullable|string|max:255',
            ],
            'referal' => [
                'from_hospital' => 'required|string|max:255',
                'to_hospital' => 'required|string|max:255',
                'from_department' => 'required|string|max:255',
                'to_department' => 'required|string|max:255',
                'clinical_findings' => 'required|string',
                'dignosis' => 'required|string',
                'rx_given' => 'required|string',
                'reason' => 'required|string',
            ],
            'police_certificate' => [
                'letter_number' => 'required|string|max:255',
                'examination_date' => 'required|date',
                'issued_idea' => 'required|string',
                'victim_history' => 'required|string',
                'injury_finding' => 'required|string',
                'doctor_recommendation' => 'required|string',
            ],
            'abortion' => [],
            'circumcision' => [],
        ];

        // Get the form type from the request
        $formType = $request->input('type');

        // Merge common rules with type-specific rules if the type exists
        $validationRules = $commonRules;
        if (isset($typeSpecificRules[$formType])) {
            $validationRules = array_merge($validationRules, $typeSpecificRules[$formType]);
        }

        // Validate the request
        $validatedData = $request->validate($validationRules);

        try {
            $exists = MedicalDocument::where('visit_id', $validatedData['visit_id'])
                ->where('type', $validatedData['type'])
                ->where('language', $validatedData['language'])
                ->exists();

            if ($exists) {
                return response()->json([
                    'error' => 'A document of this type and language already exists for this visit.',
                ], 409);
            }


            // Create a new consent form
            $medicalDocument = new MedicalDocument();

            // Set common fields
            $medicalDocument->type = $validatedData['type'];
            $medicalDocument->visit_id = $validatedData['visit_id'];
            $medicalDocument->date = $validatedData['date'];
            $medicalDocument->language = $validatedData['language'];
            $medicalDocument->notes = $validatedData['notes'] ?? null;
            $medicalDocument->filled_by = auth('admin')->user()->id;

            // Set type-specific fields
            switch ($formType) {
                case 'consent':
                    $medicalDocument->witness_1_name = $validatedData['witness_1_name'];
                    $medicalDocument->witness_1_relationship = $validatedData['witness_1_relationship'];
                    $medicalDocument->witness_2_name = $validatedData['witness_2_name'];
                    $medicalDocument->witness_2_relationship = $validatedData['witness_2_relationship'];
                    break;
                case 'certification':
                    $medicalDocument->diagnosis = $validatedData['diagnosis'];
                    $medicalDocument->date_of_rest = $validatedData['date_of_rest'];
                    break;
                case 'examination':
                    $medicalDocument->to = $validatedData['to'];
                    $medicalDocument->number = $validatedData['number'];
                    // Patient Self Declaration
                    $medicalDocument->past_diseases = $validatedData['past_diseases'] ?? null;
                    $medicalDocument->hospitalization_history = $validatedData['hospitalization_history'] ?? null;
                    $medicalDocument->self_declaration_verified = $validatedData['self_declaration_verified'] ?? null;
                    $medicalDocument->patient_signature = $validatedData['patient_signature'] ?? null;
                    $medicalDocument->patient_signature_date = $validatedData['patient_signature_date'] ?? null;
                    // Doctor's Examination
                    $medicalDocument->general_appearance = $validatedData['general_appearance'] ?? null;
                    $medicalDocument->visual_acuity_od = $validatedData['visual_acuity_od'] ?? null;
                    $medicalDocument->visual_acuity_os = $validatedData['visual_acuity_os'] ?? null;
                    $medicalDocument->hearing_test = $validatedData['hearing_test'] ?? null;
                    $medicalDocument->lung_examination = $validatedData['lung_examination'] ?? null;
                    $medicalDocument->lung_xray = $validatedData['lung_xray'] ?? null;
                    $medicalDocument->heart_condition = $validatedData['heart_condition'] ?? null;
                    $medicalDocument->blood_pressure = $validatedData['blood_pressure'] ?? null;
                    $medicalDocument->pulse = $validatedData['pulse'] ?? null;
                    $medicalDocument->abdomen_examination = $validatedData['abdomen_examination'] ?? null;
                    $medicalDocument->gut_examination = $validatedData['gut_examination'] ?? null;
                    $medicalDocument->musculoskeletal_examination = $validatedData['musculoskeletal_examination'] ?? null;
                    $medicalDocument->mental_status = $validatedData['mental_status'] ?? null;
                    $medicalDocument->nervous_system_symptoms = $validatedData['nervous_system_symptoms'] ?? null;
                    // Laboratory Examination
                    $medicalDocument->hiv_result = $validatedData['hiv_result'] ?? null;
                    $medicalDocument->syphilis_result = $validatedData['syphilis_result'] ?? null;
                    $medicalDocument->hbsag_result = $validatedData['hbsag_result'] ?? null;
                    $medicalDocument->wbc_result = $validatedData['wbc_result'] ?? null;
                    $medicalDocument->hcv_result = $validatedData['hcv_result'] ?? null;
                    $medicalDocument->esr_result = $validatedData['esr_result'] ?? null;
                    $medicalDocument->blood_group = $validatedData['blood_group'] ?? null;
                    $medicalDocument->pregnancy_test = $validatedData['pregnancy_test'] ?? null;
                    // Final Statement
                    $medicalDocument->final_medical_status = $validatedData['final_medical_status'] ?? null;
                    break;
                case 'referal':
                    $medicalDocument->from_hospital = $validatedData['from_hospital'];
                    $medicalDocument->to_hospital = $validatedData['to_hospital'];
                    $medicalDocument->from_department = $validatedData['from_department'];
                    $medicalDocument->to_department = $validatedData['to_department'];
                    $medicalDocument->clinical_findings = $validatedData['clinical_findings'];
                    $medicalDocument->dignosis = $validatedData['dignosis'];
                    $medicalDocument->rx_given = $validatedData['rx_given'];
                    $medicalDocument->reason = $validatedData['reason'];
                    break;
                case 'police_certificate':
                    $medicalDocument->letter_number = $validatedData['letter_number'];
                    $medicalDocument->examination_date = $validatedData['examination_date'];
                    $medicalDocument->issued_idea = $validatedData['issued_idea'];
                    $medicalDocument->victim_history = $validatedData['victim_history'];
                    $medicalDocument->injury_finding = $validatedData['injury_finding'];
                    $medicalDocument->doctor_recommendation = $validatedData['doctor_recommendation'];
                    break;
                    // For abortion and circumcision, no specific fields are required
            }

            $medicalDocument->save();

            return response()->json($medicalDocument, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function edit($id)
    {
        $medicalDocument = MedicalDocument::findOrFail($id);
        // Return JSON response for AJAX requests
        if (request()->ajax()) {
            return response()->json($medicalDocument);
        }

        $patients = Patient::all();
        $doctors = Admin::all(); // Assuming you have an Admin model
        return view('admin-views.consent-forms.edit', compact('medicalDocument', 'patients', 'doctors'));
    }

    public function update(Request $request, $id)
    {
        $medicalDocument = MedicalDocument::findOrFail($id);
        // Common validation rules for all form types
        $commonRules = [
            'type' => 'required|in:abortion,consent,certification,examination,referal,circumcision,police_certificate',
            'visit_id' => 'required|exists:visits,id',
            'date' => 'required|date',
            'language' => 'required|in:amharic,english',
            'notes' => 'nullable|string',
        ];

        // Type-specific validation rules
        $typeSpecificRules = [
            'consent' => [
                'witness_1_name' => 'required|string|max:255',
                'witness_1_relationship' => 'required|string|max:255',
                'witness_2_name' => 'required|string|max:255',
                'witness_2_relationship' => 'required|string|max:255',
            ],
            'certification' => [
                'diagnosis' => 'required|string|max:255',
                'date_of_rest' => 'required|numeric',
            ],
            'examination' => [
                'to' => 'required|string|max:255',
                'number' => 'required|string|max:255',
                // Patient Self Declaration
                'past_diseases' => 'nullable|string',
                'hospitalization_history' => 'nullable|string',
                'self_declaration_verified' => 'nullable|string|max:255',
                'patient_signature' => 'nullable|string|max:255',
                'patient_signature_date' => 'nullable|date',
                // Doctor's Examination
                'general_appearance' => 'nullable|string',
                'visual_acuity_od' => 'nullable|string|max:255',
                'visual_acuity_os' => 'nullable|string|max:255',
                'hearing_test' => 'nullable|string|max:255',
                'lung_examination' => 'nullable|string',
                'lung_xray' => 'nullable|string|max:255',
                'heart_condition' => 'nullable|string',
                'blood_pressure' => 'nullable|string|max:255',
                'pulse' => 'nullable|string|max:255',
                'abdomen_examination' => 'nullable|string',
                'gut_examination' => 'nullable|string',
                'musculoskeletal_examination' => 'nullable|string',
                'mental_status' => 'nullable|string|max:255',
                'nervous_system_symptoms' => 'nullable|string|max:255',
                // Laboratory Examination
                'hiv_result' => 'nullable|string|max:255',
                'syphilis_result' => 'nullable|string|max:255',
                'hbsag_result' => 'nullable|string|max:255',
                'wbc_result' => 'nullable|string|max:255',
                'hcv_result' => 'nullable|string|max:255',
                'esr_result' => 'nullable|string|max:255',
                'blood_group' => 'nullable|string|max:255',
                'pregnancy_test' => 'nullable|string|max:255',
                // Final Statement
                'final_medical_status' => 'nullable|string|max:255',
            ],
            'referal' => [
                'from_hospital' => 'required|string|max:255',
                'to_hospital' => 'required|string|max:255',
                'from_department' => 'required|string|max:255',
                'to_department' => 'required|string|max:255',
                'clinical_findings' => 'required|string',
                'dignosis' => 'required|string',
                'rx_given' => 'required|string',
                'reason' => 'required|string',
            ],
            'police_certificate' => [
                'letter_number' => 'required|string|max:255',
                'examination_date' => 'required|date',
                'issued_idea' => 'required|string',
                'victim_history' => 'required|string',
                'injury_finding' => 'required|string',
                'doctor_recommendation' => 'required|string',
            ],
            'abortion' => [],
            'circumcision' => [],
        ];

        // Get the form type from the request
        $formType = $request->input('type');

        // Merge common rules with type-specific rules if the type exists
        $validationRules = $commonRules;
        if (isset($typeSpecificRules[$formType])) {
            $validationRules = array_merge($validationRules, $typeSpecificRules[$formType]);
        }

        // Validate the request
        $validatedData = $request->validate($validationRules);

        try {
            // Update common fields
            $medicalDocument->type = $validatedData['type'];
            $medicalDocument->visit_id = $validatedData['visit_id'];
            $medicalDocument->date = $validatedData['date'];
            $medicalDocument->language = $validatedData['language'];
            $medicalDocument->notes = $validatedData['notes'] ?? null;

            // Update type-specific fields
            switch ($formType) {
                case 'consent':
                    $medicalDocument->witness_1_name = $validatedData['witness_1_name'];
                    $medicalDocument->witness_1_relationship = $validatedData['witness_1_relationship'];
                    $medicalDocument->witness_2_name = $validatedData['witness_2_name'];
                    $medicalDocument->witness_2_relationship = $validatedData['witness_2_relationship'];
                    break;
                case 'certification':
                    $medicalDocument->diagnosis = $validatedData['diagnosis'];
                    $medicalDocument->date_of_rest = $validatedData['date_of_rest'];
                    break;
                case 'examination':
                    $medicalDocument->to = $validatedData['to'];
                    $medicalDocument->number = $validatedData['number'];
                    // Patient Self Declaration
                    $medicalDocument->past_diseases = $validatedData['past_diseases'] ?? null;
                    $medicalDocument->hospitalization_history = $validatedData['hospitalization_history'] ?? null;
                    $medicalDocument->self_declaration_verified = $validatedData['self_declaration_verified'] ?? null;
                    $medicalDocument->patient_signature = $validatedData['patient_signature'] ?? null;
                    $medicalDocument->patient_signature_date = $validatedData['patient_signature_date'] ?? null;
                    // Doctor's Examination
                    $medicalDocument->general_appearance = $validatedData['general_appearance'] ?? null;
                    $medicalDocument->visual_acuity_od = $validatedData['visual_acuity_od'] ?? null;
                    $medicalDocument->visual_acuity_os = $validatedData['visual_acuity_os'] ?? null;
                    $medicalDocument->hearing_test = $validatedData['hearing_test'] ?? null;
                    $medicalDocument->lung_examination = $validatedData['lung_examination'] ?? null;
                    $medicalDocument->lung_xray = $validatedData['lung_xray'] ?? null;
                    $medicalDocument->heart_condition = $validatedData['heart_condition'] ?? null;
                    $medicalDocument->blood_pressure = $validatedData['blood_pressure'] ?? null;
                    $medicalDocument->pulse = $validatedData['pulse'] ?? null;
                    $medicalDocument->abdomen_examination = $validatedData['abdomen_examination'] ?? null;
                    $medicalDocument->gut_examination = $validatedData['gut_examination'] ?? null;
                    $medicalDocument->musculoskeletal_examination = $validatedData['musculoskeletal_examination'] ?? null;
                    $medicalDocument->mental_status = $validatedData['mental_status'] ?? null;
                    $medicalDocument->nervous_system_symptoms = $validatedData['nervous_system_symptoms'] ?? null;
                    // Laboratory Examination
                    $medicalDocument->hiv_result = $validatedData['hiv_result'] ?? null;
                    $medicalDocument->syphilis_result = $validatedData['syphilis_result'] ?? null;
                    $medicalDocument->hbsag_result = $validatedData['hbsag_result'] ?? null;
                    $medicalDocument->wbc_result = $validatedData['wbc_result'] ?? null;
                    $medicalDocument->hcv_result = $validatedData['hcv_result'] ?? null;
                    $medicalDocument->esr_result = $validatedData['esr_result'] ?? null;
                    $medicalDocument->blood_group = $validatedData['blood_group'] ?? null;
                    $medicalDocument->pregnancy_test = $validatedData['pregnancy_test'] ?? null;
                    // Final Statement
                    $medicalDocument->final_medical_status = $validatedData['final_medical_status'] ?? null;
                    break;
                case 'referal':
                    $medicalDocument->from_hospital = $validatedData['from_hospital'];
                    $medicalDocument->to_hospital = $validatedData['to_hospital'];
                    $medicalDocument->from_department = $validatedData['from_department'];
                    $medicalDocument->to_department = $validatedData['to_department'];
                    $medicalDocument->clinical_findings = $validatedData['clinical_findings'];
                    $medicalDocument->dignosis = $validatedData['dignosis'];
                    $medicalDocument->rx_given = $validatedData['rx_given'];
                    $medicalDocument->reason = $validatedData['reason'];
                    break;
                case 'police_certificate':
                    $medicalDocument->letter_number = $validatedData['letter_number'];
                    $medicalDocument->examination_date = $validatedData['examination_date'];
                    $medicalDocument->issued_idea = $validatedData['issued_idea'];
                    $medicalDocument->victim_history = $validatedData['victim_history'];
                    $medicalDocument->injury_finding = $validatedData['injury_finding'];
                    $medicalDocument->doctor_recommendation = $validatedData['doctor_recommendation'];
                    break;
                    // For abortion and circumcision, no specific fields are required
            }

            $medicalDocument->save();

            return redirect()->route('admin.medical_document.list')->with('success', 'Document form updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $medicalDocument = MedicalDocument::findOrFail($id);
            $medicalDocument->delete();
            return response()->json(['success' => true, 'message' => 'Document form deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function generatePdf($id)
    {
        $medicalDocument = MedicalDocument::findOrFail($id);

        $type = str_replace(' ', '_', $medicalDocument->type);
        $language = $medicalDocument->language === 'english' ? 'en' : 'am';

        $viewPath = "admin-views.pdf-components.{$type}-form-{$language}";

        if (!view()->exists($viewPath)) {
            return response()->json(['error' => 'PDF template not found.'], 404);
        }

        // return view($viewPath, compact('medicalDocument'));

        $paperSize = $medicalDocument->type === 'certification' ? 'a5' : 'a4';

        $pdf = PDF::loadView($viewPath, compact('medicalDocument'))
            ->setPaper($paperSize, 'portrait');

        return $pdf->stream("{$type}_document.pdf", [
            'Attachment' => false,
            'Content-Disposition' => 'inline; filename="{$type}_document.pdf"',
            'Content-Type' => 'application/pdf',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
            'Pragma' => 'public',
        ]);
    }

    public function downloadPdf($id)
    {
        $medicalDocument = MedicalDocument::findOrFail($id);

        $type = str_replace(' ', '_', $medicalDocument->type);
        $language = $medicalDocument->language === 'english' ? 'en' : 'am';

        $viewPath = "admin-views.pdf-components.{$type}-form-{$language}";

        if (!view()->exists($viewPath)) {
            return response()->json(['error' => 'PDF template not found.'], 404);
        }

        $paperSize = $medicalDocument->type === 'certification' ? 'a5' : 'a4';

        $pdf = PDF::loadView($viewPath, compact('medicalDocument'))
            ->setPaper($paperSize, 'portrait');

        return $pdf->download("{$type}_document.pdf");
    }
}
