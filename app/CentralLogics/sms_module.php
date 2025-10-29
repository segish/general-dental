<?php

namespace App\CentralLogics;

use App\Models\BusinessSetting;
use Illuminate\Support\Facades\Config;
/*use Nexmo\Laravel\Facade\Nexmo;*/
use Illuminate\Notifications\Facades\Vonage;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class SMS_module
{
    public static function send($receiver, $message, $template)
    {
        $config = self::get_settings('twilio_sms');
        if (isset($config) && $config['status'] == 1) {
            $response = self::twilio($receiver, $message);
            return $response;
        }

        $config = self::get_settings('yegara_sms');
        if (isset($config) && $config['status'] == 1) {
            $response = self::yegara($receiver, $message, $template);
            return $response;
        }

        // $config = self::get_settings('2factor_sms');
        // if (isset($config) && $config['status'] == 1) {
        //     $response = self::two_factor($receiver, $otp);
        //     return $response;
        // }

        // $config = self::get_settings('msg91_sms');
        // if (isset($config) && $config['status'] == 1) {
        //     $response = self::msg_91($receiver, $otp);
        //     return $response;
        // }
        return 'not_found';
    }

    public static function twilio($receiver, $otp)
    {
        $config = self::get_settings('twilio_sms');
        $response = 'error';
        if (isset($config) && $config['status'] == 1) {
            // $message = str_replace("#OTP#", $otp, $config['otp_template']);
            $message = $otp;
            $sid = $config['sid'];
            $token = $config['token'];
            $from = $config['from'];
            try {
                $twilio = new Client($sid, $token);
                $twilio->messages
                    ->create(
                        $receiver, // to
                        array(
                            "from" => $from,
                            "messagingServiceSid" => $config['messaging_service_sid'],
                            "body" => $message
                        )
                    );
                $response = 'success';
                Log::info("message sent");
            } catch (\Exception $exception) {
                $response = 'error';
                Log::info("message not sent " . $exception->getMessage());
            }
        }
        return $response;
    }

    public static function yegara($receiver, $message, $template_id)
    {
        $config = self::get_settings('yegara_sms');
        $response = 'error';
        if (isset($config) && $config['status'] == 1) {
            // $message = str_replace("#OTP#", $otp, $config['otp_template']);
            $server = $config['server'];
            $username  = $config['username'];
            $password  = $config['password'];
            try {
                $postData = array('to' => $receiver, 'message' => $message, 'template_id' => $template_id, 'password' => $password, 'username' => $username);
                $content = json_encode($postData);
                $curl = curl_init($server);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
                curl_setopt($curl, CURLOPT_TIMEOUT, 10); // Set timeout to 30 seconds
                $json_response = curl_exec($curl);
                $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($curl);
                $response = 'success';
                Log::info("message sent");
            } catch (\Exception $exception) {
                $response = 'error';
                Log::info("message not sent " . $exception->getMessage());
            }
        }
        return $response;
    }


    // public static function nexmo($receiver, $otp)
    // {
    //     $sms_nexmo = self::get_settings('nexmo_sms');
    //     $response = 'error';
    //     if (isset($sms_nexmo) && $sms_nexmo['status'] == 1) {
    //         $message = str_replace("#OTP#", $otp, $sms_nexmo['otp_template']);
    //         try {
    //             $config = [
    //                 'api_key' => $sms_nexmo['api_key'],
    //                 'api_secret' => $sms_nexmo['api_secret'],
    //                 'signature_secret' => '',
    //                 'private_key' => '',
    //                 'application_id' => '',
    //                 'app' => ['name' => '', 'version' => ''],
    //                 'http_client' => ''
    //             ];
    //             Config::set('nexmo', $config);
    //             /* Nexmo::message()->send([
    //                 'to' => $receiver,
    //                 'from' => $sms_nexmo['from'],
    //                 'text' => $message
    //             ]);*/

    //             Vonage::message()->send([
    //                 'to' => $receiver,
    //                 'from' => $sms_nexmo['from'],
    //                 'text' => $message
    //             ]);

    //             $response = 'success';
    //         } catch (\Exception $exception) {
    //             $response = 'error';
    //         }
    //     }
    //     return $response;
    // }

    // public static function two_factor($receiver, $otp)
    // {
    //     $config = self::get_settings('2factor_sms');
    //     $response = 'error';
    //     if (isset($config) && $config['status'] == 1) {
    //         $api_key = $config['api_key'];
    //         $curl = curl_init();
    //         curl_setopt_array($curl, array(
    //             CURLOPT_URL => "https://2factor.in/API/V1/" . $api_key . "/SMS/" . $receiver . "/" . $otp . "",
    //             CURLOPT_RETURNTRANSFER => true,
    //             CURLOPT_ENCODING => "",
    //             CURLOPT_MAXREDIRS => 10,
    //             CURLOPT_TIMEOUT => 30,
    //             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //             CURLOPT_CUSTOMREQUEST => "GET",
    //         ));
    //         $response = curl_exec($curl);
    //         $err = curl_error($curl);
    //         curl_close($curl);

    //         if (!$err) {
    //             $response = 'success';
    //         } else {
    //             $response = 'error';
    //         }
    //     }
    //     return $response;
    // }

    // public static function msg_91($receiver, $otp)
    // {
    //     $config = self::get_settings('msg91_sms');
    //     $response = 'error';
    //     if (isset($config) && $config['status'] == 1) {
    //         $receiver = str_replace("+", "", $receiver);
    //         $curl = curl_init();
    //         curl_setopt_array($curl, array(
    //             CURLOPT_URL => "https://api.msg91.com/api/v5/otp?template_id=" . $config['template_id'] . "&mobile=" . $receiver . "&authkey=" . $config['authkey'] . "",
    //             CURLOPT_RETURNTRANSFER => true,
    //             CURLOPT_ENCODING => "",
    //             CURLOPT_MAXREDIRS => 10,
    //             CURLOPT_TIMEOUT => 30,
    //             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //             CURLOPT_CUSTOMREQUEST => "GET",
    //             CURLOPT_POSTFIELDS => "{\"OTP\":\"$otp\"}",
    //             CURLOPT_HTTPHEADER => array(
    //                 "content-type: application/json"
    //             ),
    //         ));
    //         $response = curl_exec($curl);
    //         $err = curl_error($curl);
    //         curl_close($curl);
    //         if (!$err) {
    //             $response = 'success';
    //         } else {
    //             $response = 'error';
    //         }
    //     }
    //     return $response;
    // }

    public static function get_settings($name)
    {
        $config = null;
        $data = BusinessSetting::where(['key' => $name])->first();
        if (isset($data)) {
            $config = json_decode($data['value'], true);
            if (is_null($config)) {
                $config = $data['value'];
            }
        }
        return $config;
    }
}
