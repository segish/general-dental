<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Currency;
use App\Models\SocialMedia;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class BusinessSettingsController extends Controller
{
    public function __construct(
        private BusinessSetting $business_setting,
        private Currency $currency,
        private SocialMedia $social_media
    ) {
        $this->middleware('checkAdminPermission:business-settings.ecom-setup,restaurant_index')->only(['restaurant_index']);
        $this->middleware('checkAdminPermission:business-settings.about-us,about_us')->only(['about_us']);
        $this->middleware('checkAdminPermission:business-settings.social-media,social_media')->only(['social_media']);
        $this->middleware('checkAdminPermission:business-settings.app-setting,app_setting_index')->only(['app_setting_index']);
    }

    /**
     * @return Application|Factory|View
     */
    public function restaurant_index(): View|Factory|Application
    {
        if (!$this->business_setting->where(['key' => 'minimum_order_value'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'minimum_order_value'], [
                'value' => 1
            ]);
        }

        if (!$this->business_setting->where(['key' => 'fav_icon'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'fav_icon'], [
                'value' => ''
            ]);
        }

        return view('admin-views.business-settings.index');
    }

    /**
     * @param $side
     * @return JsonResponse
     */
    public function currency_symbol_position($side): JsonResponse
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'currency_symbol_position'], [
            'value' => $side
        ]);
        return response()->json(['message' => translate("Symbol position is ") . $side]);
    }

    /**
     * @return JsonResponse
     */
    public function maintenance_mode(): JsonResponse
    {
        $mode = Helpers::get_business_settings('maintenance_mode');
        DB::table('business_settings')->updateOrInsert(['key' => 'maintenance_mode'], [
            'value' => isset($mode) ? !$mode : 1
        ]);
        if (!$mode) {
            return response()->json(['message' => translate("Maintenance Mode is On.")]);
        }
        return response()->json(['message' => translate("Maintenance Mode is Off.")]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function restaurant_setup(Request $request): RedirectResponse
    {
        if ($request['email_verification'] == 1) {
            $request['phone_verification'] = 0;
        } elseif ($request['phone_verification'] == 1) {
            $request['email_verification'] = 0;
        }

        // DB::table('business_settings')->updateOrInsert(['key' => 'country'], [
        //     'value' => $request['country']
        // ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'phone_verification'], [
            'value' => $request['phone_verification']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'email_verification'], [
            'value' => $request['email_verification']
        ]);


        DB::table('business_settings')->updateOrInsert(['key' => 'currency'], [
            'value' => $request['currency']
        ]);

        $curr_logo = $this->business_setting->where(['key' => 'logo'])->first();
        DB::table('business_settings')->updateOrInsert(['key' => 'logo'], [
            'value' => $request->has('logo') ? Helpers::update('setting', $curr_logo['value'], 'png', $request->file('logo')) : $curr_logo['value']
        ]);

        $pdf_header_logo = $this->business_setting->where(['key' => 'pdf_header_logo'])->first();
        DB::table('business_settings')->updateOrInsert(['key' => 'pdf_header_logo'], [
            'value' => $request->has('pdf_header_logo') ? Helpers::update('setting', $pdf_header_logo['value'], 'png', $request->file('pdf_header_logo')) : $pdf_header_logo['value']
        ]);


        $digital_stamp = $this->business_setting->where(['key' => 'digital_stamp'])->first();
        DB::table('business_settings')->updateOrInsert(['key' => 'digital_stamp'], [
            'value' => $request->has('digital_stamp') ? Helpers::update('setting', $digital_stamp['value'], 'png', $request->file('digital_stamp')) : $digital_stamp['value']
        ]);

        $pdf_footer_logo = $this->business_setting->where(['key' => 'pdf_footer_logo'])->first();
        DB::table('business_settings')->updateOrInsert(['key' => 'pdf_footer_logo'], [
            'value' => $request->has('pdf_footer_logo') ? Helpers::update('setting', $pdf_footer_logo['value'], 'png', $request->file('pdf_footer_logo')) : $pdf_footer_logo['value']
        ]);

        $pdf_water_mark = $this->business_setting->where(['key' => 'pdf_water_mark'])->first();
        $old_value = $pdf_water_mark ? $pdf_water_mark->value : null;
        DB::table('business_settings')->updateOrInsert(
            ['key' => 'pdf_water_mark'],
            [
                'value' => $request->has('pdf_water_mark')
                    ? Helpers::update('setting', $old_value, 'png', $request->file('pdf_water_mark'))
                    : $old_value
            ]
        );


        DB::table('business_settings')->updateOrInsert(['key' => 'laboratory_center_name'], [
            'value' => $request->laboratory_center_name
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'phone'], [
            'value' => $request['phone']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'email_address'], [
            'value' => $request['email_address']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'address'], [
            'value' => $request['address']
        ]);


        DB::table('business_settings')->updateOrInsert(['key' => 'footer_text'], [
            'value' => $request['footer_text']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'pdf_company_name_en'], [
            'value' => $request['pdf_company_name_en']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'pdf_company_name_other_lc'], [
            'value' => $request['pdf_company_name_other_lc']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'pdf_tel_num'], [
            'value' => $request['pdf_tel_num']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'pdf_phone_num'], [
            'value' => $request['pdf_phone_num']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'pdf_po_box_num'], [
            'value' => $request['pdf_po_box_num']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'slogan'], [
            'value' => $request['slogan']
        ]);
        // $languages = $request['language'];

        // array_unshift($languages, 'en');

        // DB::table('business_settings')->updateOrInsert(['key' => 'language'], [
        //     'value' => json_encode($languages),
        // ]);

        // DB::table('business_settings')->updateOrInsert(['key' => 'point_per_currency'], [
        //     'value' => $request['point_per_currency'],
        // ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'time_zone'], [
            'value' => $request['time_zone'],
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'pagination_limit'], [
            'value' => $request['pagination_limit'],
        ]);


        DB::table('business_settings')->updateOrInsert(['key' => 'tax'], [
            'value' => $request['tax']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'is_flexible_payment'], [
            'value' => $request['is_flexible_payment']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'is_live'], [
            'value' => $request['is_live']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'patient_reg_prefix'], [
            'value' => $request['patient_reg_prefix']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'patient_reg_suffix'], [
            'value' => $request['patient_reg_suffix']
        ]);


        if (!empty($request['tin_no'])) {
            DB::table('business_settings')->updateOrInsert(
                ['key' => 'tin_no'],
                ['value' => $request['tin_no']]
            );
        }

        $curr_fav_icon = $this->business_setting->where(['key' => 'fav_icon'])->first();
        DB::table('business_settings')->updateOrInsert(['key' => 'fav_icon'], [
            'value' => $request->has('fav_icon') ? Helpers::update('setting', $curr_fav_icon['value'], 'png', $request->file('fav_icon')) : $curr_fav_icon['value']
        ]);

        Toastr::success(translate('Settings updated!'));
        return back();
    }

    /**
     * @return Application|Factory|View
     */
    public function mail_index(): View|Factory|Application
    {
        return view('admin-views.business-settings.mail-index');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function mail_send(Request $request): JsonResponse
    {
        $response_flag = 0;
        try {
            $emailServices = Helpers::get_business_settings('mail_config');

            if (isset($emailServices['status']) && $emailServices['status'] == 1) {
                Mail::to($request->email)->send(new \App\Mail\TestEmailSender());
                $response_flag = 1;
            }
        } catch (\Exception $exception) {
            $response_flag = 2;
        }

        return response()->json(['success' => $response_flag]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function mail_config(Request $request): RedirectResponse
    {
        $this->business_setting->where(['key' => 'mail_config'])->update([
            'value' => json_encode([
                "status" => 1,
                "name" => $request['name'],
                "host" => $request['host'],
                "driver" => $request['driver'],
                "port" => $request['port'],
                "username" => $request['username'],
                "email_id" => $request['email'],
                "encryption" => $request['encryption'],
                "password" => $request['password']
            ])
        ]);
        Toastr::success(translate('Configuration updated successfully!'));
        return back();
    }

    /**
     * @param $status
     * @return JsonResponse
     */
    public function mail_config_status($status): JsonResponse
    {
        $data = Helpers::get_business_settings('mail_config');
        $data['status'] = $status == '1' ? 1 : 0;

        $this->business_setting->where(['key' => 'mail_config'])->update([
            'value' => $data,
        ]);
        return response()->json(['message' => 'Mail config status updated']);
    }

    /**
     * @return Application|Factory|View
     */
    public function payment_index(): View|Factory|Application
    {
        return view('admin-views.business-settings.payment-index');
    }

    /**
     * @param Request $request
     * @param $name
     * @return RedirectResponse
     */
    public function payment_update(Request $request, $name): RedirectResponse
    {
        if ($name == 'cash_on_delivery') {
            $payment = $this->business_setting->where('key', 'cash_on_delivery')->first();
            if (!isset($payment)) {
                DB::table('business_settings')->insert([
                    'key' => 'cash_on_delivery',
                    'value' => json_encode([
                        'status' => $request['status']
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'cash_on_delivery'])->update([
                    'key' => 'cash_on_delivery',
                    'value' => json_encode([
                        'status' => $request['status']
                    ]),
                    'updated_at' => now()
                ]);
            }
        } elseif ($name == 'digital_payment') {
            $payment = $this->business_setting->where('key', 'digital_payment')->first();
            if (!isset($payment)) {
                DB::table('business_settings')->insert([
                    'key' => 'digital_payment',
                    'value' => json_encode([
                        'status' => $request['status']
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'digital_payment'])->update([
                    'key' => 'digital_payment',
                    'value' => json_encode([
                        'status' => $request['status']
                    ]),
                    'updated_at' => now()
                ]);
            }
        } elseif ($name == 'ssl_commerz_payment') {
            $payment = $this->business_setting->where('key', 'ssl_commerz_payment')->first();
            if (!isset($payment)) {
                DB::table('business_settings')->insert([
                    'key' => 'ssl_commerz_payment',
                    'value' => json_encode([
                        'status' => 1,
                        'store_id' => '',
                        'store_password' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'ssl_commerz_payment'])->update([
                    'key' => 'ssl_commerz_payment',
                    'value' => json_encode([
                        'status' => $request['status'] == 'on' ? 1 : 0,
                        'store_id' => $request['store_id'],
                        'store_password' => $request['store_password'],
                    ]),
                    'updated_at' => now()
                ]);
            }
        } elseif ($name == 'razor_pay') {
            $payment = $this->business_setting->where('key', 'razor_pay')->first();
            if (!isset($payment)) {
                DB::table('business_settings')->insert([
                    'key' => 'razor_pay',
                    'value' => json_encode([
                        'status' => 1,
                        'razor_key' => '',
                        'razor_secret' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'razor_pay'])->update([
                    'key' => 'razor_pay',
                    'value' => json_encode([
                        'status' => $request['status'] == 'on' ? 1 : 0,
                        'razor_key' => $request['razor_key'],
                        'razor_secret' => $request['razor_secret'],
                    ]),
                    'updated_at' => now()
                ]);
            }
        } elseif ($name == 'chapa') {
            $payment = $this->business_setting->where('key', 'chapa')->first();
            if (!isset($payment)) {
                DB::table('business_settings')->insert([
                    'key' => 'chapa',
                    'value' => json_encode([
                        'status' => 1,
                        'chapa_client_id' => '',
                        'chapa_secret' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'chapa'])->update([
                    'key' => 'chapa',
                    'value' => json_encode([
                        'status' => $request['status'] == 'on' ? 1 : 0,
                        'chapa_client_id' => $request['chapa_client_id'],
                        'chapa_secret' => $request['chapa_secret'],
                    ]),
                    'updated_at' => now()
                ]);
            }
        } elseif ($name == 'paypal') {
            $payment = $this->business_setting->where('key', 'paypal')->first();
            if (!isset($payment)) {
                DB::table('business_settings')->insert([
                    'key' => 'paypal',
                    'value' => json_encode([
                        'status' => 1,
                        'paypal_client_id' => '',
                        'paypal_secret' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'paypal'])->update([
                    'key' => 'paypal',
                    'value' => json_encode([
                        'status' => $request['status'] == 'on' ? 1 : 0,
                        'paypal_client_id' => $request['paypal_client_id'],
                        'paypal_secret' => $request['paypal_secret'],
                    ]),
                    'updated_at' => now()
                ]);
            }
        } elseif ($name == 'stripe') {
            $payment = $this->business_setting->where('key', 'stripe')->first();
            if (!isset($payment)) {
                DB::table('business_settings')->insert([
                    'key' => 'stripe',
                    'value' => json_encode([
                        'status' => 1,
                        'api_key' => '',
                        'published_key' => ''
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'stripe'])->update([
                    'key' => 'stripe',
                    'value' => json_encode([
                        'status' => $request['status'] == 'on' ? 1 : 0,
                        'api_key' => $request['api_key'],
                        'published_key' => $request['published_key']
                    ]),
                    'updated_at' => now()
                ]);
            }
        } elseif ($name == 'senang_pay') {
            $payment = $this->business_setting->where('key', 'senang_pay')->first();
            if (!isset($payment)) {
                DB::table('business_settings')->insert([
                    'key' => 'senang_pay',
                    'value' => json_encode([
                        'status' => 1,
                        'secret_key' => '',
                        'merchant_id' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'senang_pay'])->update([
                    'key' => 'senang_pay',
                    'value' => json_encode([
                        'status' => $request['status'] == 'on' ? 1 : 0,
                        'secret_key' => $request['secret_key'],
                        'merchant_id' => $request['merchant_id'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'paystack') {
            $payment = $this->business_setting->where('key', 'paystack')->first();
            if (!isset($payment)) {
                DB::table('business_settings')->insert([
                    'key' => 'paystack',
                    'value' => json_encode([
                        'status' => 1,
                        'publicKey' => '',
                        'secretKey' => '',
                        'paymentUrl' => '',
                        'merchantEmail' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'paystack'])->update([
                    'key' => 'paystack',
                    'value' => json_encode([
                        'status' => $request['status'] == 'on' ? 1 : 0,
                        'publicKey' => $request['publicKey'],
                        'secretKey' => $request['secretKey'],
                        'paymentUrl' => $request['paymentUrl'],
                        'merchantEmail' => $request['merchantEmail'],
                    ]),
                    'updated_at' => now()
                ]);
            }
        } else if ($name == 'internal_point') {
            $payment = $this->business_setting->where('key', 'internal_point')->first();
            if (!isset($payment)) {
                DB::table('business_settings')->insert([
                    'key' => 'internal_point',
                    'value' => json_encode([
                        'status' => $request['status'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'internal_point'])->update([
                    'key' => 'internal_point',
                    'value' => json_encode([
                        'status' => $request['status'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } else if ($name == 'bkash') {
            DB::table('business_settings')->updateOrInsert(['key' => 'bkash'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'api_key' => $request['api_key'],
                    'api_secret' => $request['api_secret'],
                    'username' => $request['username'],
                    'password' => $request['password'],
                ])
            ]);
        } else if ($name == 'paymob') {
            DB::table('business_settings')->updateOrInsert(['key' => 'paymob'], [
                'value' => json_encode([
                    'status' => $request['status'] == 'on' ? 1 : 0,
                    'api_key' => $request['api_key'],
                    'iframe_id' => $request['iframe_id'],
                    'integration_id' => $request['integration_id'],
                    'hmac' => $request['hmac']
                ])
            ]);
        } else if ($name == 'flutterwave') {
            DB::table('business_settings')->updateOrInsert(['key' => 'flutterwave'], [
                'value' => json_encode([
                    'status' => $request['status'] == 'on' ? 1 : 0,
                    'public_key' => $request['public_key'],
                    'secret_key' => $request['secret_key'],
                    'hash' => $request['hash']
                ])
            ]);
        } else if ($name == 'mercadopago') {
            DB::table('business_settings')->updateOrInsert(['key' => 'mercadopago'], [
                'value' => json_encode([
                    'status' => $request['status'] == 'on' ? 1 : 0,
                    'public_key' => $request['public_key'],
                    'access_token' => $request['access_token']
                ])
            ]);
        }

        Toastr::success(translate('payment settings updated!'));
        return back();
    }

    /**
     * @return Application|Factory|View
     */
    public function currency_index(): View|Factory|Application
    {
        return view('admin-views.business-settings.currency-index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function currency_store(Request $request): RedirectResponse
    {
        $request->validate([
            'currency_code' => 'required|unique:currencies',
        ]);

        $this->currency->create([
            "country" => $request['country'],
            "currency_code" => $request['currency_code'],
            "currency_symbol" => $request['symbol'],
            "exchange_rate" => $request['exchange_rate'],
        ]);
        Toastr::success(translate('Currency added successfully!'));
        return back();
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function currency_edit($id): View|Factory|Application
    {
        $currency = $this->currency->find($id);
        return view('admin-views.business-settings.currency-update', compact('currency'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|RedirectResponse|Redirector
     */
    public function currency_update(Request $request, $id): Redirector|RedirectResponse|Application
    {
        $this->currency->where(['id' => $id])->update([
            "country" => $request['country'],
            "currency_code" => $request['currency_code'],
            "currency_symbol" => $request['symbol'],
            "exchange_rate" => $request['exchange_rate'],
        ]);
        Toastr::success(translate('Currency updated successfully!'));
        return redirect('admin/business-settings/currency-add');
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function currency_delete($id): RedirectResponse
    {
        $this->currency->where(['id' => $id])->delete();
        Toastr::success(translate('Currency removed successfully!'));
        return back();
    }

    /**
     * @return Application|Factory|View
     */
    public function terms_and_conditions(): View|Factory|Application
    {
        $tnc = $this->business_setting->where(['key' => 'terms_and_conditions'])->first();
        if ($tnc == false) {
            $this->business_setting->insert([
                'key' => 'terms_and_conditions',
                'value' => ''
            ]);
        }
        return view('admin-views.business-settings.terms-and-conditions', compact('tnc'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function terms_and_conditions_update(Request $request): RedirectResponse
    {
        $this->business_setting->where(['key' => 'terms_and_conditions'])->update([
            'value' => $request->tnc
        ]);
        Toastr::success(translate('Terms and Conditions updated!'));
        return back();
    }

    /**
     * @return Application|Factory|View
     */
    public function privacy_policy(): View|Factory|Application
    {
        $data = $this->business_setting->where(['key' => 'privacy_policy'])->first();
        if (!$data) {
            $data = [
                'key' => 'privacy_policy',
                'value' => '',
            ];
            $this->business_setting->insert($data);
        }
        return view('admin-views.business-settings.privacy-policy', compact('data'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function privacy_policy_update(Request $request): RedirectResponse
    {
        $this->business_setting->where(['key' => 'privacy_policy'])->update([
            'value' => $request->privacy_policy,
        ]);

        Toastr::success(translate('Privacy policy updated!'));
        return back();
    }

    /**
     * @return Application|Factory|View
     */
    public function about_us(): View|Factory|Application
    {
        $data = $this->business_setting->where(['key' => 'about_us'])->first();
        if (!$data) {
            $data = [
                'key' => 'about_us',
                'value' => '',
            ];
            $this->business_setting->insert($data);
        }
        return view('admin-views.business-settings.about-us', compact('data'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function about_us_update(Request $request): RedirectResponse
    {
        $this->business_setting->where(['key' => 'about_us'])->update([
            'value' => $request->about_us,
        ]);

        Toastr::success(translate('About us updated!'));
        return back();
    }

    /**
     * @return Application|Factory|View
     */
    public function fcm_index(): View|Factory|Application
    {
        if (!$this->business_setting->where(['key' => 'fcm_topic'])->first()) {
            $this->business_setting->insert([
                'key' => 'fcm_topic',
                'value' => ''
            ]);
        }
        if (!$this->business_setting->where(['key' => 'fcm_project_id'])->first()) {
            $this->business_setting->insert([
                'key' => 'fcm_project_id',
                'value' => ''
            ]);
        }
        if (!$this->business_setting->where(['key' => 'push_notification_key'])->first()) {
            $this->business_setting->insert([
                'key' => 'push_notification_key',
                'value' => ''
            ]);
        }

        if (!$this->business_setting->where(['key' => 'order_pending_message'])->first()) {
            $this->business_setting->insert([
                'key' => 'order_pending_message',
                'value' => json_encode([
                    'status' => 0,
                    'message' => ''
                ])
            ]);
        }

        if (!$this->business_setting->where(['key' => 'order_confirmation_msg'])->first()) {
            $this->business_setting->insert([
                'key' => 'order_confirmation_msg',
                'value' => json_encode([
                    'status' => 0,
                    'message' => ''
                ])
            ]);
        }

        if (!$this->business_setting->where(['key' => 'order_processing_message'])->first()) {
            $this->business_setting->insert([
                'key' => 'order_processing_message',
                'value' => json_encode([
                    'status' => 0,
                    'message' => ''
                ])
            ]);
        }

        if (!$this->business_setting->where(['key' => 'out_for_delivery_message'])->first()) {
            $this->business_setting->insert([
                'key' => 'out_for_delivery_message',
                'value' => json_encode([
                    'status' => 0,
                    'message' => ''
                ])
            ]);
        }

        if (!$this->business_setting->where(['key' => 'order_delivered_message'])->first()) {
            $this->business_setting->insert([
                'key' => 'order_delivered_message',
                'value' => json_encode([
                    'status' => 0,
                    'message' => ''
                ])
            ]);
        }

        if (!$this->business_setting->where(['key' => 'delivery_boy_assign_message'])->first()) {
            $this->business_setting->insert([
                'key' => 'delivery_boy_assign_message',
                'value' => json_encode([
                    'status' => 0,
                    'message' => ''
                ])
            ]);
        }

        if (!$this->business_setting->where(['key' => 'delivery_boy_start_message'])->first()) {
            $this->business_setting->insert([
                'key' => 'delivery_boy_start_message',
                'value' => json_encode([
                    'status' => 0,
                    'message' => ''
                ])
            ]);
        }

        if (!$this->business_setting->where(['key' => 'delivery_boy_delivered_message'])->first()) {
            $this->business_setting->insert([
                'key' => 'delivery_boy_delivered_message',
                'value' => json_encode([
                    'status' => 0,
                    'message' => ''
                ])
            ]);
        }

        if (!$this->business_setting->where(['key' => 'customer_notify_message'])->first()) {
            $this->business_setting->insert([
                'key' => 'customer_notify_message',
                'value' => json_encode([
                    'status' => 0,
                    'message' => '',
                ]),
            ]);
        }

        return view('admin-views.business-settings.fcm-index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update_fcm(Request $request): RedirectResponse
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'fcm_project_id'], [
            'value' => $request['fcm_project_id']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'push_notification_key'], [
            'value' => $request['push_notification_key']
        ]);

        Toastr::success(translate('Settings updated!'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update_fcm_messages(Request $request): RedirectResponse
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'order_pending_message'], [
            'value' => json_encode([
                'status' => $request['pending_status'] == 1 ? 1 : 0,
                'message' => $request['pending_message']
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'order_confirmation_msg'], [
            'value' => json_encode([
                'status' => $request['confirm_status'] == 1 ? 1 : 0,
                'message' => $request['confirm_message']
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'order_processing_message'], [
            'value' => json_encode([
                'status' => $request['processing_status'] == 1 ? 1 : 0,
                'message' => $request['processing_message']
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'out_for_delivery_message'], [
            'value' => json_encode([
                'status' => $request['out_for_delivery_status'] == 1 ? 1 : 0,
                'message' => $request['out_for_delivery_message']
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'order_delivered_message'], [
            'value' => json_encode([
                'status' => $request['delivered_status'] == 1 ? 1 : 0,
                'message' => $request['delivered_message']
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'delivery_boy_assign_message'], [
            'value' => json_encode([
                'status' => $request['delivery_boy_assign_status'] == 1 ? 1 : 0,
                'message' => $request['delivery_boy_assign_message']
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'delivery_boy_start_message'], [
            'value' => json_encode([
                'status' => $request['delivery_boy_start_status'] == 1 ? 1 : 0,
                'message' => $request['delivery_boy_start_message']
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'delivery_boy_delivered_message'], [
            'value' => json_encode([
                'status' => $request['delivery_boy_delivered_status'] == 1 ? 1 : 0,
                'message' => $request['delivery_boy_delivered_message']
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'customer_notify_message'], [
            'value' => json_encode([
                'status' => $request['customer_notify_status'] == 1 ? 1 : 0,
                'message' => $request['customer_notify_message'],
            ]),
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'returned_message'], [
            'value' => json_encode([
                'status' => $request['returned_status'] == 1 ? 1 : 0,
                'message' => $request['returned_message'],
            ]),
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'failed_message'], [
            'value' => json_encode([
                'status' => $request['failed_status'] == 1 ? 1 : 0,
                'message' => $request['failed_message'],
            ]),
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'canceled_message'], [
            'value' => json_encode([
                'status' => $request['canceled_status'] == 1 ? 1 : 0,
                'message' => $request['canceled_message'],
            ]),
        ]);

        Toastr::success(translate('Message updated!'));
        return back();
    }

    /**
     * @return Application|Factory|View
     */
    public function map_api_settings(): View|Factory|Application
    {
        return view('admin-views.business-settings.map-api');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update_map_api(Request $request): RedirectResponse
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'map_api_key'], [
            'value' => $request->map_api_key,
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'map_api_server_key'], [
            'value' => $request['map_api_server_key'],
        ]);

        Toastr::success(translate('Settings updated!'));
        return back();
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function recaptcha_index(Request $request): View|Factory|Application
    {
        return view('admin-views.business-settings.recaptcha-index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function recaptcha_update(Request $request): RedirectResponse
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'recaptcha'], [
            'key' => 'recaptcha',
            'value' => json_encode([
                'status' => $request['status'] == 'on' ? 1 : 0,
                'site_key' => $request['site_key'],
                'secret_key' => $request['secret_key']
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Toastr::success(translate('Updated Successfully'));
        return back();
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function return_page_index(Request $request): View|Factory|Application
    {
        $data = $this->business_setting->where(['key' => 'return_page'])->first();

        if ($data == false) {
            $data = [
                'key' => 'return_page',
                'value' => json_encode([
                    'status' => 0,
                    'content' => null
                ]),
            ];
            $this->business_setting->insert($data);
        }
        return view('admin-views.business-settings.return_page-index', compact('data'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function return_page_update(Request $request): RedirectResponse
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'return_page'], [
            'key' => 'return_page',
            'value' => json_encode([
                'status' => $request['status'] == 1 ? 1 : 0,
                'content' => $request->has('content') ? $request['content'] : null
            ]),
            'created_at' => now(),
            'updated_at' => now(),

        ]);

        Toastr::success(translate('Updated Successfully'));
        return back();
    }

    //refund page

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function refund_page_index(Request $request): View|Factory|Application
    {
        $data = $this->business_setting->where(['key' => 'refund_page'])->first();

        if ($data == false) {
            $data = [
                'key' => 'refund_page',
                'value' => json_encode([
                    'status' => 0,
                    'content' => null
                ]),
            ];
            $this->business_setting->insert($data);
        }

        return view('admin-views.business-settings.refund_page-index', compact('data'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function refund_page_update(Request $request): RedirectResponse
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'refund_page'], [
            'key' => 'refund_page',
            'value' => json_encode([
                'status' => $request['status'] == 1 ? 1 : 0,
                'content' => $request->has('content') ? $request['content'] : null
            ]),
            'created_at' => now(),
            'updated_at' => now(),

        ]);


        Toastr::success(translate('Updated Successfully'));
        return back();
    }


    //cancellation page

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function cancellation_page_index(Request $request): View|Factory|Application
    {
        $data = $this->business_setting->where(['key' => 'cancellation_page'])->first();

        if ($data == false) {
            $data = [
                'key' => 'cancellation_page',
                'value' => json_encode([
                    'status' => 0,
                    'content' => null
                ]),
            ];
            $this->business_setting->insert($data);
        }

        return view('admin-views.business-settings.cancellation_page-index', compact('data'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function cancellation_page_update(Request $request): RedirectResponse
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'cancellation_page'], [
            'key' => 'cancellation_page',
            'value' => json_encode([
                'status' => $request['status'] == 1 ? 1 : 0,
                'content' => $request->has('content') ? $request['content'] : null
            ]),
            'created_at' => now(),
            'updated_at' => now(),

        ]);

        Toastr::success(translate('Updated Successfully'));
        return back();
    }

    /**
     * @return Application|Factory|View
     */
    public function app_setting_index(): View|Factory|Application
    {
        return View('admin-views.business-settings.app-setting-index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function app_setting_update(Request $request): RedirectResponse
    {
        if ($request->platform == 'android') {
            DB::table('business_settings')->updateOrInsert(['key' => 'play_store_config'], [
                'value' => json_encode([
                    'status' => $request['play_store_status'],
                    'link' => $request['play_store_link'],
                    'min_version' => $request['android_min_version'],

                ]),
            ]);

            Toastr::success(translate('Updated Successfully for Android'));
            return back();
        }

        if ($request->platform == 'ios') {
            DB::table('business_settings')->updateOrInsert(['key' => 'app_store_config'], [
                'value' => json_encode([
                    'status' => $request['app_store_status'],
                    'link' => $request['app_store_link'],
                    'min_version' => $request['ios_min_version'],
                ]),
            ]);

            Toastr::success(translate('Updated Successfully for IOS'));
            return back();
        }


        Toastr::error(translate('Updated failed'));
        return back();
    }

    /**
     * @return Application|Factory|View
     */
    public function firebase_message_config_index(): View|Factory|Application
    {
        return View('admin-views.business-settings.firebase-config-index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function firebase_message_config(Request $request): RedirectResponse
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'firebase_message_config'], [
            'key' => 'firebase_message_config',
            'value' => json_encode([
                'apiKey' => $request['apiKey'],
                'authDomain' => $request['authDomain'],
                'projectId' => $request['projectId'],
                'storageBucket' => $request['storageBucket'],
                'messagingSenderId' => $request['messagingSenderId'],
                'appId' => $request['appId'],
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        self::firebase_message_config_file_gen();

        Toastr::success(translate('Config Updated Successfully'));
        return back();
    }

    /**
     * @return void
     */
    function firebase_message_config_file_gen(): void
    {
        //configs
        $config = \App\CentralLogics\Helpers::get_business_settings('firebase_message_config');
        $apiKey = $config['apiKey'] ?? '';
        $authDomain = $config['authDomain'] ?? '';
        $projectId = $config['projectId'] ?? '';
        $storageBucket = $config['storageBucket'] ?? '';
        $messagingSenderId = $config['messagingSenderId'] ?? '';
        $appId = $config['appId'] ?? '';

        try {
            $old_file = fopen("firebase-messaging-sw.js", "w") or die("Unable to open file!");

            $new_text = "importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js');\n";
            $new_text .= "importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js');\n";
            $new_text .= 'firebase.initializeApp({apiKey: "' . $apiKey . '",authDomain: "' . $authDomain . '",projectId: "' . $projectId . '",storageBucket: "' . $storageBucket . '", messagingSenderId: "' . $messagingSenderId . '", appId: "' . $appId . '"});';
            $new_text .= "\nconst messaging = firebase.messaging();\n";
            $new_text .= "messaging.setBackgroundMessageHandler(function (payload) { return self.registration.showNotification(payload.data.title, { body: payload.data.body ? payload.data.body : '', icon: payload.data.icon ? payload.data.icon : '' }); });";
            $new_text .= "\n";

            fwrite($old_file, $new_text);
            fclose($old_file);
        } catch (\Exception $exception) {
        }
    }

    /**
     * @return Application|Factory|View
     */
    public function social_media(): View|Factory|Application
    {
        // $about_us = $this->business_setting->where('type', 'about_us')->first();
        return view('admin-views.business-settings.social-media');
    }

    /**
     * @param Request $request
     * @return JsonResponse|void
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->social_media->orderBy('id', 'desc')->get();
            return response()->json($data);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function social_media_store(Request $request): JsonResponse
    {
        try {
            $this->social_media->updateOrInsert([
                'name' => $request->get('name'),
            ], [
                'name' => $request->get('name'),
                'link' => $request->get('link'),
            ]);

            return response()->json([
                'success' => 1,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 1,
            ]);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function social_media_edit(Request $request): JsonResponse
    {
        $data = $this->social_media->where('id', $request->id)->first();
        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function social_media_update(Request $request): JsonResponse
    {
        $social_media = $this->social_media->find($request->id);
        $social_media->name = $request->name;
        $social_media->link = $request->link;
        $social_media->save();
        return response()->json();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function social_media_delete(Request $request): JsonResponse
    {
        $br = $this->social_media->find($request->id);
        $br->delete();
        return response()->json();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function social_media_status_update(Request $request): JsonResponse
    {
        $this->social_media->where(['id' => $request['id']])->update([
            'status' => $request['status'],
        ]);
        return response()->json([
            'success' => 1,
        ], 200);
    }

    /**
     * @return Application|Factory|View
     */
    public function otp_index(): Factory|View|Application
    {
        return view('admin-views.business-settings.otp-setup');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update_otp(Request $request): RedirectResponse
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'maximum_otp_hit'], [
            'value' => $request['maximum_otp_hit'],
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'otp_resend_time'], [
            'value' => $request['otp_resend_time'],
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'temporary_block_time'], [
            'value' => $request['temporary_block_time'],
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'maximum_login_hit'], [
            'value' => $request['maximum_login_hit'],
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'temporary_login_block_time'], [
            'value' => $request['temporary_login_block_time'],
        ]);

        Toastr::success(translate('Settings updated!'));
        return back();
    }

    /**
     * @return Application|Factory|View
     */
    public function cookies_setup(): Factory|View|Application
    {
        return view('admin-views.business-settings.cookies-setup');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function cookies_setup_update(Request $request): RedirectResponse
    {
        //dd($request->all());
        DB::table('business_settings')->updateOrInsert(['key' => 'cookies'], [
            'value' => json_encode([
                'status' => $request['status'],
                'text' => $request['text'],
            ])
        ]);

        Toastr::success(translate('Settings updated!'));
        return back();
    }

    /**
     * @return Application|Factory|View
     */
    public function delivery_fee_setup(): Factory|View|Application
    {
        return view('admin-views.business-settings.delivery-fee');
    }

    /**
     * @return Application|Factory|View
     */
    public function social_media_login(): Factory|View|Application
    {
        return view('admin-views.business-settings.social-media-login');
    }

    /**
     * @param $medium
     * @param $status
     * @return JsonResponse
     */
    public function change_social_login_status($medium, $status): JsonResponse
    {
        if ($medium == 'google') {
            DB::table('business_settings')->updateOrInsert(['key' => 'google_social_login'], [
                'value' => $status
            ]);
        } elseif ($medium == 'facebook') {
            DB::table('business_settings')->updateOrInsert(['key' => 'facebook_social_login'], [
                'value' => $status
            ]);
        }
        return response()->json(['message' => 'Status updated']);
    }

    /**
     * @return Application|Factory|View
     */
    public function social_media_chat(): Factory|View|Application
    {
        if (!$this->business_setting->where(['key' => 'whatsapp'])->first()) {
            $this->business_setting->insert([
                'key' => 'whatsapp',
                'value' => json_encode([
                    'status' => 0,
                    'number' => '',
                ]),
            ]);
        }

        if (!$this->business_setting->where(['key' => 'telegram'])->first()) {
            $this->business_setting->insert([
                'key' => 'telegram',
                'value' => json_encode([
                    'status' => 0,
                    'user_name' => '',
                ]),
            ]);
        }

        if (!$this->business_setting->where(['key' => 'messenger'])->first()) {
            $this->business_setting->insert([
                'key' => 'messenger',
                'value' => json_encode([
                    'status' => 0,
                    'user_name' => '',
                ]),
            ]);
        }
        return view('admin-views.business-settings.chat-index');
    }

    public function update_media_chat(Request $request): \Illuminate\Http\RedirectResponse
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'whatsapp'], [
            'value' => json_encode([
                'status' => $request['whatsapp_status'] == 1 ? 1 : 0,
                'number' => $request['whatsapp_number'],
            ]),
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'telegram'], [
            'value' => json_encode([
                'status' => $request['telegram_status'] == 1 ? 1 : 0,
                'user_name' => $request['telegram_user_name'],
            ]),
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'messenger'], [
            'value' => json_encode([
                'status' => $request['messenger_status'] == 1 ? 1 : 0,
                'user_name' => $request['messenger_user_name'],
            ]),
        ]);

        Toastr::success(translate('Settings updated!'));
        return back();
    }
}
