<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BillingReportExport implements FromView
{
    protected $billings;

    public function __construct($billings)
    {
        $this->billings = $billings;
    }

    public function view(): View
    {
        return view('admin-views.billings.report_excel', [
            'billings' => $this->billings,
        ]);
    }
}
