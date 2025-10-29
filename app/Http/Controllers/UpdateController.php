<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use App\Models\Admin;
use App\Models\BusinessSetting;
use App\Traits\ActivationClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class UpdateController extends Controller
{
    use ActivationClass;

    public function update_software_index()
    {
        return view('update.update-software');
    }

    public function update_software(Request $request)
    {
        Helpers::setEnvironmentValue('SOFTWARE_ID','MzExNTc0NTQ=');
        Helpers::setEnvironmentValue('BUYER_USERNAME',$request['username']);
        Helpers::setEnvironmentValue('PURCHASE_CODE',$request['purchase_key']);
        Helpers::setEnvironmentValue('APP_MODE','live');
        Helpers::setEnvironmentValue('SOFTWARE_VERSION','7.0');
        Helpers::setEnvironmentValue('APP_NAME','Hexacom');

        if ($this->actch()) {
            return redirect(base64_decode('aHR0cHM6Ly82YW10ZWNoLmNvbS9zb2Z0d2FyZS1hY3RpdmF0aW9u'));
        }

        Artisan::call('migrate', ['--force' => true]);

        $previousRouteServiceProvier = base_path('app/Providers/RouteServiceProvider.php');
        $newRouteServiceProvier = base_path('app/Providers/RouteServiceProvider.txt');
        copy($newRouteServiceProvier, $previousRouteServiceProvier);

        Artisan::call('optimize:clear');

        if (!BusinessSetting::where(['key' => 'terms_and_conditions'])->first()) {
            BusinessSetting::insert([
                'key' => 'terms_and_conditions',
                'value' => ''
            ]);
        }
        if (!BusinessSetting::where(['key' => 'razor_pay'])->first()) {
            BusinessSetting::insert([
                'key' => 'razor_pay',
                'value' => '{"status":"1","razor_key":"","razor_secret":""}'
            ]);
        }
        if (!BusinessSetting::where(['key' => 'minimum_order_value'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'minimum_order_value'], [
                'value' => 1
            ]);
        }
        if (!BusinessSetting::where(['key' => 'point_per_currency'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'point_per_currency'], [
                'value' => 1
            ]);
        }
        if (!BusinessSetting::where(['key' => 'language'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'language'], [
                'value' => json_encode(["en"])
            ]);
        }
        if (!BusinessSetting::where(['key' => 'time_zone'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'time_zone'], [
                'value' => 'Pacific/Midway'
            ]);
        }
        if (!BusinessSetting::where(['key' => 'internal_point'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'internal_point'], [
                'value' => json_encode(['status'=>0])
            ]);
        }
        if (!BusinessSetting::where(['key' => 'privacy_policy'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'privacy_policy'], [
                'value' => ''
            ]);
        }
        if (!BusinessSetting::where(['key' => 'about_us'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'about_us'], [
                'value' => ''
            ]);
        }

        DB::table('business_settings')->updateOrInsert(['key' => 'phone_verification'], [
            'value' => 0
        ]);
        // DB::table('business_settings')->updateOrInsert(['key' => 'msg91_sms'], [
        //     'key' => 'msg91_sms',
        //     'value' => '{"status":0,"template_id":null,"authkey":null}'
        // ]);
        // DB::table('business_settings')->updateOrInsert(['key' => '2factor_sms'], [
        //     'key' => '2factor_sms',
        //     'value' => '{"status":"0","api_key":null}'
        // ]);
        // DB::table('business_settings')->updateOrInsert(['key' => 'nexmo_sms'], [
        //     'key' => 'nexmo_sms',
        //     'value' => '{"status":0,"api_key":null,"api_secret":null,"signature_secret":"","private_key":"","application_id":"","from":null,"otp_template":null}'
        // ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'twilio_sms'], [
            'key' => 'twilio_sms',
            'value' => '{"status":0,"sid":null,"token":null,"from":null,"otp_template":null}'
        ]);

        if (!BusinessSetting::where(['key' => 'pagination_limit'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'pagination_limit'], [
                'value' => 10
            ]);
        }
        if (!BusinessSetting::where(['key' => 'map_api_key'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'map_api_key'], [
                'value' => ''
            ]);
        }
        if (!BusinessSetting::where(['key' => 'play_store_config'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'play_store_config'], [
                'value' => '{"status":"","link":"","min_version":""}'
            ]);
        }
        if (!BusinessSetting::where(['key' => 'app_store_config'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'app_store_config'], [
                'value' => '{"status":"","link":"","min_version":""}'
            ]);
        }
        if (!BusinessSetting::where(['key' => 'delivery_management'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'delivery_management'], [
                'value' => json_encode([
                    'status' => 0,
                    'min_shipping_charge' => 0,
                    'shipping_per_km' => 0,
                ]),
            ]);
        }

        DB::table('branches')->insertOrIgnore([
            'id' => 1,
            'name' => 'Main Branch',
            'email' => '',
            'password' => '',
            'coverage' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if (!BusinessSetting::where(['key' => 'dm_self_registration'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'dm_self_registration'], [
                'value' => 1
            ]);
        }

        if (!BusinessSetting::where(['key' => 'maximum_otp_hit'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'maximum_otp_hit'], [
                'value' => 5
            ]);
        }

        if (!BusinessSetting::where(['key' => 'otp_resend_time'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'otp_resend_time'], [
                'value' => 60
            ]);
        }

        if (!BusinessSetting::where(['key' => 'temporary_block_time'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'temporary_block_time'], [
                'value' => 120
            ]);
        }

        if (!BusinessSetting::where(['key' => 'maximum_login_hit'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'maximum_login_hit'], [
                'value' => 5
            ]);
        }

        if (!BusinessSetting::where(['key' => 'temporary_login_block_time'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'temporary_login_block_time'], [
                'value' => 120
            ]);
        }

        if (!BusinessSetting::where(['key' => 'cookies'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'cookies'], [
                'value' => '{"status":"1","text":"Allow Cookies for this site"}'
            ]);
        }

        $mail_config = \App\CentralLogics\Helpers::get_business_settings('mail_config');
        BusinessSetting::where(['key' => 'mail_config'])->update([
            'value' => json_encode([
                "status" => 0,
                "name" => $mail_config['name'],
                "host" => $mail_config['host'],
                "driver" => $mail_config['driver'],
                "port" => $mail_config['port'],
                "username" => $mail_config['username'],
                "email_id" => $mail_config['email_id'],
                "encryption" => $mail_config['encryption'],
                "password" => $mail_config['password']
            ]),
        ]);

        if (!BusinessSetting::where(['key' => 'fav_icon'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'fav_icon'], [
                'value' => ''
            ]);
        }

        $api_key = Helpers::get_business_settings('map_api_key');
        if (!BusinessSetting::where(['key' => 'map_api_server_key'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'map_api_server_key'], [
                'value' => $api_key
            ]);
        }

        if (!BusinessSetting::where(['key' => 'google_social_login'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'google_social_login'], [
                'value' => 1
            ]);
        }

        if (!BusinessSetting::where(['key' => 'facebook_social_login'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'facebook_social_login'], [
                'value' => 1
            ]);
        }

        if (!BusinessSetting::where(['key' => 'whatsapp'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'whatsapp'], [
                'value' => '{"status":"0","number":""}'
            ]);
        }

        if (!BusinessSetting::where(['key' => 'telegram'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'telegram'], [
                'value' => '{"status":"0","user_name":""}'
            ]);
        }

        if (!BusinessSetting::where(['key' => 'messenger'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'messenger'], [
                'value' => '{"status":"0","user_name":""}'
            ]);
        }

        return redirect('/admin/auth/login');
    }
}