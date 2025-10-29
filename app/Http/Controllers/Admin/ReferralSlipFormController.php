<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralSlipForm;
use App\Models\Patient;
use App\Models\Admin;
use App\Models\Visit;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\View\View;

class ReferralSlipFormController extends Controller
{
    private ReferralSlipForm $referralSlip;

    public function __construct(ReferralSlipForm $referralSlip)
    {
        $this->referralSlip = $referralSlip;
    }

    public function index()
    {
        $patients = Patient::all();
        $visits = Visit::all();
        return view('admin-views.referral_slip.index', compact('patients', 'visits'));
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $currentUser = auth('admin')->user();
        $search = $request['search'];

        $query = $this->referralSlip->latest();
        $referralSlips = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.referral_slip.list', compact('referralSlips',));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'visit_id' => 'required|exists:visits,id',
            'to_department' => 'required|string|max:255',
            'from_department' => 'required|string|max:255',
            'date' => 'required|date',
            'clinical_finding' => 'required|string',
            'diagnosis' => 'required|string|max:255',
            'investigation_result' => 'nullable|string',
            'rx_given' => 'nullable|string',
            'reasons_for_referral' => 'required|string',
            'referred_by' => 'required|string|max:255',
            'finding' => 'nullable|string',
            'treatment_given' => 'nullable|string',
        ]);

        try {
            $exists = ReferralSlipForm::where('visit_id', $validatedData['visit_id'])->exists();

            if ($exists) {
                return response()->json([
                    'error' => 'A consent form already exists for this visit.',
                ], 409); // HTTP 409 Conflict
            }
            $referralSlip = new ReferralSlipForm();

            $referralSlip->visit_id = $validatedData['visit_id'];
            $referralSlip->to_department = $validatedData['to_department'];
            $referralSlip->from_department = $validatedData['from_department'];
            $referralSlip->date = $validatedData['date'];
            $referralSlip->clinical_finding = $validatedData['clinical_finding'];
            $referralSlip->diagnosis = $validatedData['diagnosis'];
            $referralSlip->investigation_result = $validatedData['investigation_result'] ?? null;
            $referralSlip->rx_given = $validatedData['rx_given'] ?? null;
            $referralSlip->reasons_for_referral = $validatedData['reasons_for_referral'];
            $referralSlip->referred_by = $validatedData['referred_by'];
            $referralSlip->finding = $validatedData['finding'] ?? null;
            $referralSlip->filled_by = auth('admin')->user()->id;
            $referralSlip->treatment_given = $validatedData['treatment_given'] ?? null;

            $referralSlip->save();

            return response()->json($referralSlip, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function edit(ReferralSlipForm $referralSlip)
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        return view('admin-views.referral_slip.edit', compact('referralSlip', 'patients', 'doctors'));
    }

    public function update(Request $request, ReferralSlipForm $referralSlip)
    {
        $validatedData = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'to_department' => 'required|string|max:255',
            'from_department' => 'required|string|max:255',
            'time' => 'required|date_format:H:i',
            'clinical_finding' => 'required|string',
            'diagnosis' => 'required|string|max:255',
            'investigation_result' => 'nullable|string',
            'rx_given' => 'nullable|string',
            'reasons_for_referral' => 'required|string',
            'referred_by' => 'required|string|max:255',
            'doctor_id' => 'required|exists:doctors,id',
            'finding' => 'nullable|string',
            'treatment_given' => 'nullable|string',
        ]);

        $referralSlip->update($validatedData);

        return redirect()->route('referral_slip.index')->with('success', 'Referral slip updated successfully.');
    }

    public function destroy(ReferralSlipForm $referralSlip)
    {
        $referralSlip->delete();
        return redirect()->route('referral_slip.index')->with('success', 'Referral slip deleted successfully.');
    }

    public function generatePdf($id)
    {
        $referralSlip = ReferralSlipForm::findOrFail($id);
        $pdf = PDF::loadView('admin-views.referral_slip.pdf', compact('referralSlip'))
            ->setPaper('a4', 'portrait');
        return $pdf->stream('referralSlip.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline; filename="referralSlip.pdf"',
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function downloadPdf($id)
    {
        $referralSlip = ReferralSlipForm::findOrFail($id);
        $pdf = PDF::loadView('admin-views.referral_slip.pdf', compact('referralSlip'))
            ->setPaper('a4', 'portrait');
        return $pdf->download('referralSlip.pdf');
    }
}
