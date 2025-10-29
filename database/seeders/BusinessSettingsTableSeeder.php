<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'key' => 'laboratory_center_name',
                'value' => 'Test Laboratory',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 2,
                'key' => 'currency',
                'value' => 'ETB',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 3,
                'key' => 'logo',
                'value' => '2021-06-12-60c493426bd7a.png',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 4,
                'key' => 'mail_config',
                'value' => '{"status":0,"name":"Delivery APP","host":"mail.demo.com","driver":"smtp","port":"587","username":"info@demo.com","email_id":"info@demo.com","encryption":"tls","password":"demo"}',
                'created_at' => null,
                'updated_at' => '2023-06-19 17:47:20',
            ],
            [
                'id' => 5,
                'key' => 'phone',
                'value' => '+251900000000',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 6,
                'key' => 'footer_text',
                'value' => 'copyright',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 7,
                'key' => 'address',
                'value' => 'Addis Ababa, Ethiopia',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 8,
                'key' => 'email_verification',
                'value' => '0',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 9,
                'key' => 'language',
                'value' => '["en"]',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 10,
                'key' => 'time_zone',
                'value' => 'Africa/Nairobi',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 11,
                'key' => 'phone_verification',
                'value' => '0',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 12,
                'key' => 'msg91_sms',
                'value' => '{"status":0,"template_id":null,"authkey":null}',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 13,
                'key' => '2factor_sms',
                'value' => '{"status":"0","api_key":null}',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 14,
                'key' => 'nexmo_sms',
                'value' => '{"status":0,"api_key":null,"api_secret":null,"signature_secret":"","private_key":"","application_id":"","from":null,"otp_template":null}',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 15,
                'key' => 'email_address',
                'value' => 'example@info.com',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 16,
                'key' => 'twilio_sms',
                'value' => '{"status":0,"sid":null,"token":null,"from":null,"otp_template":null}',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 17,
                'key' => 'pagination_limit',
                'value' => '10',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 18,
                'key' => 'cookies',
                'value' => '{"status":"1","text":"Allow Cookies for this site"}',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 19,
                'key' => 'fav_icon',
                'value' => '',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 20,
                'key' => 'tax',
                'value' => 0,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 21,
                'key' => 'pdf_header_logo',
                'value' => '2021-06-12-60c493426bd7a.png',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 22,
                'key' => 'pdf_footer_logo',
                'value' => '2021-06-12-60c493426bd7a.png',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 23,
                'key' => 'pdf_company_name_en',
                'value' => 'Company Name',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 24,
                'key' => 'pdf_company_name_other_lc',
                'value' => 'የድርጅቱ ስም',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 25,
                'key' => 'pdf_tel_num',
                'value' => '0110000000',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 26,
                'key' => 'pdf_phone_num',
                'value' => '0900000000',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 27,
                'key' => 'pdf_po_box_num',
                'value' => '1000',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 28,
                'key' => 'slogan',
                'value' => 'It is all about your life...',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 29,
                'key' => 'is_flexible_payment',
                'value' => true,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 30,
                'key' => 'currency_symbol_position',
                'value' => 'right',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 31,
                'key' => 'has_pharmacy',
                'value' => false,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 32,
                'key' => 'patient_reg_prefix',
                'value' => 'REG-',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 33,
                'key' => 'patient_reg_suffix',
                'value' => '-PT',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 34,
                'key' => 'pdf_water_mark',
                'value' => '2021-06-12-60c493426bd7a.png',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 35,
                'key' => 'is_live',
                'value' => false,
                'created_at' => null,
                'updated_at' => null,
            ],
        ];

        DB::table('business_settings')->insert($data);
    }
}
