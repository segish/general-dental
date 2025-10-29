<?php

namespace App\Exports;

use App\Models\Billing;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RevenueExport implements FromView
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        $revenues = Billing::whereBetween('bill_date', [$this->startDate, $this->endDate])
            ->select(
                DB::raw('DATE(bill_date) as date'),
                DB::raw('SUM(total_amount - discount) as total_revenue'),
                DB::raw('SUM(amount_paid) as total_paid'),
                DB::raw('SUM((total_amount - discount) - amount_paid) as outstanding')
            )
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        return view('admin-views.report.exports.revenue_excel', compact('revenues'));
    }
}
