<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\Branch;
use App\Models\LaboratoryRequestTest;
use App\Models\LaboratoryRequest;
use App\Models\Visit;
use App\Models\labtest;
use App\Models\Patient;
use App\Exports\RevenueExport;
use App\Exports\GenericExport;
use App\Models\Specimen;
use Box\Spout\Common\Exception\InvalidArgumentException;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function __construct(
        private LaboratoryRequest $laboratoryRequest,
        private Patient $patients,
        private Billing $billing,
        private Specimen $Specimen,
    ) {

        $this->middleware('checkAdminPermission:report.test,test')->only(['test']);
        $this->middleware('checkAdminPermission:report.patients,patients')->only(['patients']);
        $this->middleware('checkAdminPermission:report.revenue,billing')->only(['billing']);
        $this->middleware('checkAdminPermission:report.specimens,specimens')->only(['specimens']);
    }

    private function authorizeAccess($permission, $actions)
    {
        // Simple authorization check
        if (auth('admin')->check()) {
            return;
        }

        abort(403, 'Unauthorized');
    }
    /**
     * @return Application|Factory|View
     */
    public function testReport(Request $request)
    {
        // Handle period parameter for quick filters
        if ($request->has('period')) {
            switch ($request->period) {
                case 'today':
                    $fromDate = Carbon::today()->startOfDay();
                    $toDate = Carbon::today()->endOfDay();
                    break;
                case 'this_week':
                    $fromDate = Carbon::now()->startOfWeek();
                    $toDate = Carbon::now()->endOfWeek();
                    break;
                case 'this_month':
                    $fromDate = Carbon::now()->startOfMonth();
                    $toDate = Carbon::now()->endOfMonth();
                    break;
                case 'this_year':
                    $fromDate = Carbon::now()->startOfYear();
                    $toDate = Carbon::now()->endOfYear();
                    break;
                default:
                    $fromDate = Carbon::now()->startOfYear();
                    $toDate = Carbon::now()->endOfDay();
            }
        } else {
            $fromDate = $request->has('from_date') ? Carbon::parse($request->from_date)->startOfDay() : Carbon::now()->startOfYear();
            $toDate = $request->has('to_date') ? Carbon::parse($request->to_date)->endOfDay() : Carbon::now()->endOfDay();
        }

        // If download is requested
        if ($request->has('download')) {
            return $this->downloadTestReport($fromDate, $toDate, $request->download);
        }

        $totalTests = [
            'today' => $this->laboratoryRequest->whereDate('created_at', today())->count(),
            'this_week' => $this->laboratoryRequest
                ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->count(),
            'this_month' => $this->laboratoryRequest
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_year' => $this->laboratoryRequest
                ->whereYear('created_at', now()->year)
                ->count(),
            'filtered' => $this->laboratoryRequest
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->count(),
        ];

        $statusCompleted = [
            'today' => $this->laboratoryRequest->whereDate('created_at', today())->where('status', 'completed')->count(),
            'this_week' => $this->laboratoryRequest
                ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->where('status', 'completed')
                ->count(),
            'this_month' => $this->laboratoryRequest
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('status', 'completed')
                ->count(),
            'this_year' => $this->laboratoryRequest
                ->whereYear('created_at', now()->year)
                ->where('status', 'completed')
                ->count(),
            'filtered' => $this->laboratoryRequest
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->where('status', 'completed')
                ->count(),
        ];

        $statusRejected = [
            'today' => $this->laboratoryRequest->whereDate('created_at', today())->where('status', 'rejected')->count(),
            'this_week' => $this->laboratoryRequest
                ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->where('status', 'rejected')
                ->count(),
            'this_month' => $this->laboratoryRequest
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('status', 'rejected')
                ->count(),
            'this_year' => $this->laboratoryRequest
                ->whereYear('created_at', now()->year)
                ->where('status', 'rejected')
                ->count(),
            'filtered' => $this->laboratoryRequest
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->where('status', 'rejected')
                ->count(),
        ];

        $statusPending = [
            'today' => $this->laboratoryRequest->whereDate('created_at', today())->where('status', 'pending')->count(),
            'this_week' => $this->laboratoryRequest
                ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->where('status', 'pending')
                ->count(),
            'this_month' => $this->laboratoryRequest
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('status', 'pending')
                ->count(),
            'this_year' => $this->laboratoryRequest
                ->whereYear('created_at', now()->year)
                ->where('status', 'pending')
                ->count(),
            'filtered' => $this->laboratoryRequest
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->where('status', 'pending')
                ->count(),
        ];

        $testsByType = [
            'today' => DB::table('laboratory_request_test')
                ->join('tests', 'laboratory_request_test.test_id', '=', 'tests.id')
                ->whereDate('laboratory_request_test.created_at', today())
                ->select('tests.test_name as test_type', DB::raw('COUNT(*) as count'))
                ->groupBy('tests.test_name')
                ->orderBy('count', 'desc') // Order by count in descending order
                ->limit(5)                // Limit to top 5
                ->get(),

            // Tests by Type This Week
            'this_Week' => DB::table('laboratory_request_test')
                ->join('tests', 'laboratory_request_test.test_id', '=', 'tests.id')
                ->whereBetween('laboratory_request_test.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->select('tests.test_name as test_type', DB::raw('COUNT(*) as count'))
                ->groupBy('tests.test_name')
                ->orderBy('count', 'desc') // Order by count in descending order
                ->limit(5)                // Limit to top 5
                ->get(),

            // Tests by Type This Month
            'this_month' => DB::table('laboratory_request_test')
                ->join('tests', 'laboratory_request_test.test_id', '=', 'tests.id')
                ->whereMonth('laboratory_request_test.created_at', now()->month)
                ->select('tests.test_name as test_type', DB::raw('COUNT(*) as count'))
                ->groupBy('tests.test_name')
                ->orderBy('count', 'desc') // Order by count in descending order
                ->limit(5)                // Limit to top 5
                ->get(),

            // Tests by Type This Year
            'this_year' => DB::table('laboratory_request_test')
                ->join('tests', 'laboratory_request_test.test_id', '=', 'tests.id')
                ->whereYear('laboratory_request_test.created_at', now()->year)
                ->select('tests.test_name as test_type', DB::raw('COUNT(*) as count'))
                ->groupBy('tests.test_name')
                ->orderBy('count', 'desc') // Order by count in descending order
                ->limit(5)                // Limit to top 5
                ->get(),

            // Tests by Type Filtered
            'filtered' => DB::table('laboratory_request_test')
                ->join('tests', 'laboratory_request_test.test_id', '=', 'tests.id')
                ->whereBetween('laboratory_request_test.created_at', [$fromDate, $toDate])
                ->select('tests.test_name as test_type', DB::raw('COUNT(*) as count'))
                ->groupBy('tests.test_name')
                ->orderBy('count', 'desc') // Order by count in descending order
                ->limit(5)                // Limit to top 5
                ->get()
        ];

        // Use filtered data if date range is provided, otherwise use yearly data
        $testsByTypeToday = $request->has('from_date') && $request->has('to_date') || $request->has('period')
            ? $testsByType['filtered']
            : $testsByType['this_year'];

        return view('admin-views.report.test', compact('totalTests', 'statusCompleted', 'statusRejected', 'statusPending', 'testsByTypeToday', 'fromDate', 'toDate'));
    }

    /**
     * Download test report in specified format
     */
    private function downloadTestReport($fromDate, $toDate, $format)
    {
        $data = [
            'totalTests' => $this->laboratoryRequest->whereBetween('created_at', [$fromDate, $toDate])->count(),
            'statusCompleted' => $this->laboratoryRequest->whereBetween('created_at', [$fromDate, $toDate])->where('status', 'completed')->count(),
            'statusRejected' => $this->laboratoryRequest->whereBetween('created_at', [$fromDate, $toDate])->where('status', 'rejected')->count(),
            'statusPending' => $this->laboratoryRequest->whereBetween('created_at', [$fromDate, $toDate])->where('status', 'pending')->count(),
            'testsByType' => DB::table('laboratory_request_test')
                ->join('tests', 'laboratory_request_test.test_id', '=', 'tests.id')
                ->whereBetween('laboratory_request_test.created_at', [$fromDate, $toDate])
                ->select('tests.test_name as test_type', DB::raw('COUNT(*) as count'))
                ->groupBy('tests.test_name')
                ->orderBy('count', 'desc')
                ->get(),
            'fromDate' => $fromDate,
            'toDate' => $toDate
        ];

        if ($format === 'excel') {
            return Excel::download(new GenericExport($data, 'admin-views.report.exports.test_report'), 'test_report.xlsx');
        } elseif ($format === 'pdf') {
            $pdf = PDF::loadView('admin-views.report.exports.test_report', ['data' => $data]);
            return $pdf->download('test_report.pdf');
        }

        abort(400, 'Unsupported file format');
    }

    public function order_index(): Factory|View|Application
    {
        if (!session()->has('from_date')) {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
        }

        return view('admin-views.report.order-index');
    }

    /**
     * @return Application|Factory|View
     */
    public function earning_index(): Factory|View|Application
    {
        if (!session()->has('from_date')) {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
        }
        return view('admin-views.report.earning-index');
    }

    public function patientReport(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        // Fetch visits within date range with patients
        $visits = Visit::with('patient')
            ->whereBetween('visit_datetime', [$startDate, $endDate])
            ->get();

        // Unique patients visited
        $totalPatientsVisited = $visits->pluck('patient_id')->unique()->count();

        // OPD/IPD
        $ipdVisits = $visits->where('visit_type', 'IPD')->count();
        $opdVisits = $visits->where('visit_type', 'OPD')->count();

        // Total visits
        $totalVisits = $visits->count();

        // New patients (first visit is within the selected range)
        $newPatientIds = Visit::select('patient_id')
            ->groupBy('patient_id')
            ->havingRaw('MIN(visit_datetime) BETWEEN ? AND ?', [$startDate, $endDate])
            ->pluck('patient_id');

        $newPatientsCount = $newPatientIds->count();
        // Initialize counts
        $ageGroups = [
            '0–12' => 0,
            '13–18' => 0,
            '19–35' => 0,
            '36–60' => 0,
            '60+' => 0,
        ];

        foreach ($visits as $visit) {
            $age = $visit->patient->age ?? null;

            if ($age !== null) {
                if ($age <= 12) {
                    $ageGroups['0–12']++;
                } elseif ($age <= 18) {
                    $ageGroups['13–18']++;
                } elseif ($age <= 35) {
                    $ageGroups['19–35']++;
                } elseif ($age <= 60) {
                    $ageGroups['36–60']++;
                } else {
                    $ageGroups['60+']++;
                }
            }
        }

        $topLabTests = LaboratoryRequestTest::whereHas('laboratoryRequest', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })
            ->select('test_id', DB::raw('COUNT(*) as total'))
            ->groupBy('test_id')
            ->with('test:id,test_name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('admin-views.report.patient-report', compact('ageGroups', 'startDate', 'endDate', 'totalPatientsVisited', 'ipdVisits', 'opdVisits', 'totalVisits', 'topLabTests'));
    }

    public function generatePatientReport(Request $request, $report_type)
    {
        $fileFormat = $request->input('file_format'); // 'pdf' or 'excel'
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        switch ($report_type) {
            case 'patient_demographics':
                $data = $this->getPatientDemographics($startDate, $endDate);
                $view = 'admin-views.report.exports.patient_demographics';
                $fileName = 'patient_demographics_report';

                break;

            case 'patient_visit_summary':
                $data = $this->getPatientVisitSummary($startDate, $endDate);
                $view = 'admin-views.report.exports.patient_visit_summary';
                $fileName = 'patient_visit_summary_report';
                break;

            case 'visit_frequency_by_patient':
                $data = $this->getVisitFrequencyReport($startDate, $endDate);
                $view = 'admin-views.report.exports.visit_frequency_by_patient';
                $fileName = 'visit_frequency_by_patient_report';
                break;

            case 'laboratory_test_report':
                $data = $this->getLabTestReport($startDate, $endDate);
                $view = 'admin-views.report.exports.laboratory_test_report';
                $fileName = 'lab_test_report';
                break;

            case 'billing_summary_report':
                $data = $this->getBillingSummaryReport($startDate, $endDate);
                $view = 'admin-views.report.exports.billing_summary_report';
                $fileName = 'billing_summary_report';
                break;

            default:
                abort(404, 'Invalid report type');
        }

        // Return based on requested format
        if ($fileFormat === 'excel') {
            return Excel::download(new GenericExport($data, $view), "$fileName.xlsx");
        } elseif ($fileFormat === 'pdf') {
            $pdf = Pdf::loadView($view, ['data' => $data]);
            return $pdf->download("$fileName.pdf");
        }

        abort(400, 'Unsupported file format');
    }


    public function revenueReport(Request $request)
    {
        $user = auth('admin')->user();

        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        $total_bills = DB::table('billings')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('is_canceled', false)
            ->count();

        $revenues = Billing::whereBetween('bill_date', [$startDate, $endDate])
            ->where('is_canceled', false)
            ->select(
                DB::raw('DATE(bill_date) as date'),
                DB::raw('SUM(total_amount - discount) as total_revenue'),
                DB::raw('SUM(amount_paid) as total_paid'),
                DB::raw('SUM(CASE WHEN amount_paid < (total_amount - discount) THEN amount_paid ELSE 0 END) as partial_paid'),
                DB::raw('SUM((total_amount - discount) - amount_paid) as outstanding')
            )
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();
        $revenueByService = Billing::whereBetween('bill_date', [$startDate, $endDate])
            ->where('is_canceled', false)
            ->select(
                DB::raw('
                    CASE
                        WHEN laboratory_request_id IS NOT NULL THEN "Laboratory Service"
                        WHEN billing_service_id IS NOT NULL THEN "Billing Service"
                        WHEN emergency_medicine_issuance_id IS NOT NULL THEN "Emergency Medication"
                        WHEN patient_procedures_id IS NOT NULL THEN "Patient Procedures"
                        ELSE "Other"
                    END as service_type
                '),
                DB::raw('SUM(total_amount - discount) as total_revenue'),
                DB::raw('SUM(amount_paid) as total_paid'),
                DB::raw('SUM((total_amount - discount) - amount_paid) as outstanding')
            )
            ->groupBy('service_type')
            ->get();

        $chartData = [
            'labels' => $revenues->pluck('date'),
            'total_revenue' => $revenues->pluck('total_revenue'),
            'total_paid' => $revenues->pluck('total_paid'),
            'outstanding' => $revenues->pluck('outstanding'),
        ];

        Log::error($chartData);
        $earning = [];

        if ($user->can('dashboard.earning-statistics')) {
            $from = now()->startOfYear()->format('Y-m-d');
            $to = now()->endOfYear()->format('Y-m-d');
            $earning_data = $this->billing->select(
                DB::raw('IFNULL(sum(total_amount),0) as sums'),
                DB::raw('YEAR(created_at) year, MONTH(created_at) month')
            )
                ->where('is_canceled', false)
                ->whereBetween('created_at', [$from, $to])
                ->groupby('year', 'month')
                ->get()
                ->toArray();

            for ($inc = 1; $inc <= 12; $inc++) {
                $earning[$inc] = 0;
                foreach ($earning_data as $match) {
                    if ($match['month'] == $inc) {
                        $earning[$inc] = $match['sums'];
                    }
                }
            }
        }

        $total_paid = $revenues->sum('total_paid');
        $total_unpaid = $revenues->sum('outstanding');
        $partial_paid = $revenues->sum('partial_paid');
        return view('admin-views.report.revenue-report', compact('revenues', 'earning', 'total_bills', 'total_paid', 'partial_paid', 'total_unpaid', 'revenueByService', 'chartData', 'startDate', 'endDate'));
    }

    public function DiseaseReport(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();


        // 1. Unique Diseases Diagnosed
        $uniqueDiseasesDiagnosed = Visit::whereBetween('created_at', [$startDate, $endDate])
            ->whereHas('diagnosisTreatment.diseases') // Ensure diagnosis treatment has diseases
            ->with('diagnosisTreatment.diseases')
            ->get()
            ->pluck('diagnosisTreatment.diseases')
            ->flatten()
            ->unique('id')
            ->count();

        // 2. OPD Visits with Diseases
        $opdVisitsWithDiseases = Visit::whereBetween('created_at', [$startDate, $endDate])
            ->where('visit_type', 'OPD')
            ->whereHas('diagnosisTreatment.diseases') // Ensure diagnosis treatment has diseases
            ->count();

        // 3. IPD Visits with Diseases
        $ipdVisitsWithDiseases = Visit::whereBetween('created_at', [$startDate, $endDate])
            ->where('visit_type', 'IPD')
            ->whereHas('diagnosisTreatment.diseases') // Ensure diagnosis treatment has diseases
            ->count();

        // 4. Total Visits with Diseases (OPD + IPD)
        $totalVisitsWithDiseases = Visit::whereBetween('created_at', [$startDate, $endDate])
            ->whereHas('diagnosisTreatment.diseases') // Ensure diagnosis treatment has diseases
            ->count();

        // Pass the data to the view
        return view('admin-views.report.disease-report', compact(
            'startDate',
            'endDate',
            'uniqueDiseasesDiagnosed',
            'opdVisitsWithDiseases',
            'ipdVisitsWithDiseases',
            'totalVisitsWithDiseases'
        ));
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function set_date(Request $request): RedirectResponse
    {
        $fromDate = \Carbon\Carbon::parse($request['from'])->startOfDay();
        $toDate = Carbon::parse($request['to'])->endOfDay();

        session()->put('from_date', $fromDate);
        session()->put('to_date', $toDate);
        return back();
    }

    public function downloadExcel(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? now()->endOfMonth()->toDateString();

        return Excel::download(new RevenueExport($startDate, $endDate), 'revenue_report.xlsx');
    }

    // Download PDF
    public function downloadPDF(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        $revenues = Billing::whereBetween('bill_date', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(bill_date) as date'),
                DB::raw('SUM(total_amount - discount) as total_revenue'),
                DB::raw('SUM(amount_paid) as total_paid'),
                DB::raw('SUM((total_amount - discount) - amount_paid) as outstanding')
            )
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();
        $pdf = Pdf::loadView('admin-views.report.exports.revenue_pdf', compact('revenues', 'startDate', 'endDate'));
        $pdf = PDF::loadView('admin-views.report.exports.revenue_pdf', compact('revenues', 'startDate', 'endDate'));

        return $pdf->download('revenue_report.pdf');
    }

    private function getPatientDemographics($startDate, $endDate)
    {
        return Patient::whereHas('visits', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate]);
        })->get();
    }

    private function getPatientVisitSummary($startDate, $endDate)
    {
        return Visit::with(['patient', 'medicalRecord', 'diagnosisTreatment.diseases'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('patient_id')
            ->get();
    }

    private function getVisitFrequencyReport($startDate, $endDate)
    {
        return Visit::select('patient_id')
            ->selectRaw('COUNT(*) as visit_count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('patient_id')
            ->with('patient')
            ->get();
    }

    private function getLabTestReport($startDate, $endDate)
    {
        return Visit::with(['laboratoryRequest.tests.test', 'patient']) // Loading necessary relationships
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->map(function ($visit) {
                // Check if laboratoryRequest exists and has tests
                if ($visit->laboratoryRequest && $visit->laboratoryRequest->tests->isNotEmpty()) {
                    $tests = $visit->laboratoryRequest->tests->map(function ($test) {
                        return $test->test->test_name ?? 'N/A'; // Get test name or 'N/A' if not available
                    });

                    // Attach the tests to the visit object
                    $visit->test_names = $tests->implode(', '); // Combine test names into a single string
                } else {
                    $visit->test_names = 'N/A'; // No laboratory request or tests
                }

                return $visit;
            });
    }

    private function getBillingSummaryReport($startDate, $endDate)
    {
        return Billing::with('patient')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
    }
}
