<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Visit;
use App\Models\Appointment;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Patient;
use App\Models\LaboratoryRequestTest;
use App\Models\LaboratoryRequest;
use App\Models\TestResult;
use App\Models\Specimen;
use App\Models\Billing;
use App\Models\Department;
use Illuminate\Support\Facades\Gate;

class SystemController extends Controller
{
    public function __construct(
        private Admin $admin,
        private Billing $billing,
        private Department $department,
        private Appointment $appointment,

    ) {

        $this->middleware('checkAdminPermission:dashboard,dashboard')->only(['dashboard']);
    }

    /**
     * @param $id
     * @return string
     */
    public function fcm($id): string
    {
        $fcm_token = $this->admin->find(auth('admin')->id())->fcm_token;
        $data = [
            'title' => 'New auto generate message arrived from admin dashboard',
            'description' => $id,
            'order_id' => '',
            'image' => '',
            'type' => 'general',
        ];
        Helpers::send_push_notif_to_device($fcm_token, $data);

        return "Notification sent to admin";
    }

    /**
     * @return Application|Factory|View
     */
    public function dashboard(): Factory|View|Application
    {
        $user = auth('admin')->user();

        $data = [];

        // Patient Count
        if ($user->can('dashboard.view_patient_count')) {
            $data['patient_count'] = Patient::count();
        }
        $with = [
            'billingDetail.test',
            'billingDetail.dischargeService.visit.ipdRecord.bed',
            'billingDetail.radiology',
            'billingDetail.billingService',
            'billingDetail.prescreption.medicine.medicine',
            'payments',
            'visit.patient',
            'admin',
            'canceledByAdmin'
        ];

        // todays billing
        if ($user->can('dashboard.view_todays_billings_list')) {
            $data['todays_billing'] = Billing::with($with)
                ->whereDate('created_at', Carbon::today())
                ->orderBy('created_at', 'desc')
                ->get();
        }
        // todays billing
        if ($user->can('dashboard.view_todays_laboratory_requests')) {
            $data['todays_lab_requset'] = LaboratoryRequest::whereDate('created_at', Carbon::today())
                // ->where('doctor_id', auth('admin')->id())
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // todays visit
        if ($user->can('dashboard.view_todays_doctor_visit_list')) {
            $data['todays_doctor_visit'] = Visit::whereDate('created_at', Carbon::today())
                ->where('doctor_id', auth('admin')->id())
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // todays visit
        if ($user->can('dashboard.view_todays_visit_list')) {
            $data['todays_visit'] = Visit::whereDate('created_at', Carbon::today())
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Total Revenue Today
        if ($user->can('dashboard.view_revenue')) {
            $data['total_amount_today'] = $this->billing
                ->whereDate('bill_date', today())
                ->sum('total_amount');
        }

        // Staff Count
        if ($user->can('dashboard.view_staff_count')) {
            $data['staff'] = $this->admin->count();
            $data['patient'] = Patient::all()->count();
            $data['department'] = $this->department->count();
        }

        if ($user->can('dashboard.view_pending_tests')) {
            $data['pending_tests'] = LaboratoryRequestTest::where('status', 'pending')->count();
        }

        if ($user->can('dashboard.view_completed_tests')) {
            $data['completed_tests'] = LaboratoryRequestTest::where('status', 'completed')->count();
        }

        if ($user->can('dashboard.view_total_samples_collected')) {
            $data['total_samples_collected'] = Specimen::whereMonth('created_at', now()->month)->count();
        }

        if ($user->can('dashboard.view_critical_alerts')) {
            $data['critical_alerts'] = LaboratoryRequest::where('order_status', 'urgent')->count();
        }

        if ($user->can('dashboard.view_patients_registered_today')) {
            $data['patients_registered_today'] = Patient::whereDate('created_at', today())->count();
        }

        if ($user->can('dashboard.view_pending_payments')) {
            $data['pending_payments'] = $this->billing->where('status', 'pending')->count();
        }

        if ($user->can('dashboard.view_samples_received_today')) {
            $data['samples_received_today'] = Specimen::whereDate('created_at', today())->count();
        }

        if ($user->can('dashboard.view_tests_completed_today')) {
            $data['tests_completed_today'] = LaboratoryRequestTest::whereDate('created_at', today())
                ->where('status', 'completed')
                ->count();
        }

        if ($user->can('dashboard.view_test_result_processed_today')) {
            $data['test_result_processed_today'] = TestResult::whereDate('process_end_time', today())->count();
        }

        if ($user->can('dashboard.view_test_result_approved_today')) {
            $data['test_result_approved_today'] = TestResult::whereDate('process_end_time', today())
                ->where('verify_status', 'approved')
                ->count();
        }

        if ($user->can('dashboard.view_pending_test_reports')) {
            $data['pending_test_reports'] = LaboratoryRequestTest::where('status', 'pending')->count();
        }

        if ($user->can('dashboard.view_pending_sample_collections')) {
            $data['pending_sample_collections'] = Specimen::where('status', 'pending')->count();
        }

        if ($user->can('dashboard.view_rejected_samples')) {
            $data['rejected_samples'] = Specimen::where('status', 'rejected')->count();
        }

        if ($user->can('dashboard.view_critical_samples_tests')) {
            $data['critical_samples_tests'] = LaboratoryRequest::where('order_status', 'urgent')->count();
        }

        // Department Count
        if ($user->can('dashboard.view_department_count')) {
            $data['department_count'] = $this->department->count();
        }

        $earning = [];

        if ($user->can('dashboard.earning-statistics')) {
            $from = now()->startOfYear()->format('Y-m-d');
            $to = now()->endOfYear()->format('Y-m-d');
            $earning_data = $this->billing->select(
                DB::raw('IFNULL(sum(total_amount),0) as sums'),
                DB::raw('YEAR(created_at) year, MONTH(created_at) month')
            )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();
            for ($inc = 1; $inc <= 12; $inc++) {
                $earning[$inc] = 0;
                foreach ($earning_data as $match) {
                    if ($match['month'] == $inc) {
                        $earning[$inc] = $match['sums'];
                    }
                }
            }
        }
        return view('admin-views.dashboard', compact('data', 'earning'));
    }

    /**
     * @return Application|Factory|View
     */
    public function settings(): Factory|View|Application
    {
        return view('admin-views.settings');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function settings_update(Request $request): RedirectResponse
    {
        $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ], [
            'f_name.required' => 'First name is required!',
            'l_name.required' => 'Last name is required!',
        ]);

        $admin = $this->admin->find(auth('admin')->id());
        $admin->f_name = $request->f_name;
        $admin->l_name = $request->l_name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->image = $request->has('image') ? Helpers::update('avatar', $admin->image, $request->file('image')->extension(), $request->file('image')) : $admin->image;
        $admin->save();
        Toastr::success(translate('Admin updated successfully!'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function settings_password_update(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required|same:confirm_password|min:8',
            'confirm_password' => 'required',
        ]);

        $admin = $this->admin->find(auth('admin')->id());
        $admin->password = bcrypt($request['password']);
        $admin->save();
        Toastr::success(translate('Admin password updated successfully!'));
        return back();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_earning_statitics(Request $request): JsonResponse
    {
        $dateType = $request->type;

        $earning_data = array();
        if ($dateType == 'yearEarn') {
            $number = 12;
            $from = \Illuminate\Support\Carbon::now()->startOfYear()->format('Y-m-d');
            $to = Carbon::now()->endOfYear()->format('Y-m-d');

            $earning = $this->billing->select(
                DB::raw('IFNULL(sum(total_amount),0) as sums'),
                DB::raw('YEAR(created_at) year, MONTH(created_at) month')
            )->where('is_canceled', false)
                ->whereBetween('created_at', [$from, $to])
                ->groupby('year', 'month')
                ->get()
                ->toArray();

            for ($inc = 1; $inc <= $number; $inc++) {
                $earning_data[$inc] = 0;
                foreach ($earning as $match) {
                    if ($match['month'] == $inc) {
                        $earning_data[$inc] = $match['sums'];
                    }
                }
            }
            $key_range = array("Jan", "Feb", "Mar", "April", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
        } elseif ($dateType == 'MonthEarn') {
            $from = date('Y-m-01');
            $to = date('Y-m-t');
            $number = date('d', strtotime($to));
            $key_range = range(1, $number);

            $earning = $this->billing->select(
                DB::raw('IFNULL(sum(total_amount),0) as sums'),
                DB::raw('YEAR(created_at) year, MONTH(created_at) month, DAY(created_at) day')
            )->where('is_canceled', false)
                ->whereBetween('created_at', [$from, $to])
                ->groupby('year', 'month', 'day')
                ->get()
                ->toArray();

            for ($inc = 1; $inc <= $number; $inc++) {
                $earning_data[$inc] = 0;
                foreach ($earning as $match) {
                    if ($match['day'] == $inc) {
                        $earning_data[$inc] = $match['sums'];
                    }
                }
            }
        } elseif ($dateType == 'WeekEarn') {
            Carbon::setWeekStartsAt(Carbon::SUNDAY);
            Carbon::setWeekEndsAt(Carbon::SATURDAY);

            $from = Carbon::now()->startOfWeek()->format('Y-m-d 00:00:00');
            $to = Carbon::now()->endOfWeek()->format('Y-m-d 23:59:59');
            $date_range = CarbonPeriod::create($from, $to)->toArray();
            $day_range = array();
            foreach ($date_range as $date) {
                $day_range[] = $date->format('d');
            }
            $day_range = array_flip($day_range);
            $day_range_keys = array_keys($day_range);
            $day_range_values = array_values($day_range);
            $day_range_intKeys = array_map('intval', $day_range_keys);
            $day_range = array_combine($day_range_intKeys, $day_range_values);

            $earning = $this->billing->select(
                DB::raw('IFNULL(sum(total_amount),0) as sums'),
                DB::raw('YEAR(created_at) year, MONTH(created_at) month, DAY(created_at) day')
            )->where('is_canceled', false)
                ->whereBetween('created_at', [$from, $to])
                ->groupByRaw('YEAR(created_at), MONTH(created_at), DAY(created_at)')
                ->orderByRaw('YEAR(created_at), MONTH(created_at), DAY(created_at)')
                ->pluck('sums', 'day')
                ->toArray();

            $earning_data = array();
            foreach ($day_range as $day => $value) {
                $day_value = 0;
                $earning_data[$day] = $day_value;
            }

            foreach ($earning as $order_day => $order_value) {
                if (array_key_exists($order_day, $earning_data)) {
                    $earning_data[$order_day] = $order_value;
                }
            }

            $key_range = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        }

        $label = $key_range;
        $earning_data_final = $earning_data;

        $data = array(
            'earning_label' => $label,
            'earning' => array_values($earning_data_final),
        );
        return response()->json($data);
    }

    public function ignore_check_order()
    {
        $this->order->where(['checked' => 0])->update(['checked' => 1]);
        return redirect()->back();
    }
}
