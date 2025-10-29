<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\PharmacyCompanySetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class PharmacyCompanySettingController extends Controller
{
    public function __construct(
        private PharmacyCompanySetting $business_setting,
    ) {}
    /**
     * @return Application|Factory|View
     */
    public function pharmacy_index(): View|Factory|Application
    {
        return view('admin-views.business-settings.pharmacy-index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function pharmacy_setup(Request $request): RedirectResponse
    {

        DB::table('pharmacy_company_settings')->updateOrInsert(['key' => 'company_name'], [
            'value' => $request->company_name
        ]);

        $curr_logo = $this->business_setting->where(['key' => 'logo'])->first();
        DB::table('pharmacy_company_settings')->updateOrInsert(['key' => 'logo'], [
            'value' => $request->has('logo') ? Helpers::update('setting', $curr_logo['value'], 'png', $request->file('logo')) : $curr_logo['value']
        ]);

        DB::table('pharmacy_company_settings')->updateOrInsert(['key' => 'phone'], [
            'value' => $request['phone']
        ]);

        DB::table('pharmacy_company_settings')->updateOrInsert(['key' => 'vat_reg_no'], [
            'value' => $request['vat_reg_no']
        ]);

        DB::table('pharmacy_company_settings')->updateOrInsert(['key' => 'tin_no'], [
            'value' => $request['tin_no']
        ]);

        DB::table('pharmacy_company_settings')->updateOrInsert(['key' => 'address'], [
            'value' => $request['address']
        ]);

        Toastr::success(translate('Settings updated!'));
        return back();
    }
}
