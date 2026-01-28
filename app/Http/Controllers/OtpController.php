<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OtpController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate(['phone_number' => 'required|string']);
        
        $phone = $request->phone_number;
        $otp = rand(1000, 9999);

        // Store OTP in Cache for 5 minutes
        \Illuminate\Support\Facades\Cache::put('otp_' . $phone, $otp, 300);

        // Send SMS via Gateway
        try {
            $response = \Illuminate\Support\Facades\Http::get(env('SMS_API_URL') . '/sendtext', [
                'apikey' => env('SMS_API_KEY'),
                'secretkey' => env('SMS_SECRET_KEY'),
                'callerID' => env('SMS_CALLER_ID'),
                'toUser' => $phone,
                'messageContent' => "Your verification code is: $otp",
            ]);

            if ($response->successful()) {
                return response()->json(['message' => 'OTP sent successfully']);
            } else {
                \Illuminate\Support\Facades\Log::error('SMS Gateway Error: ' . $response->body());
                return response()->json(['message' => 'Failed to send OTP via SMS Gateway'], 500);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('OTP Exception: ' . $e->getMessage());
            // Fallback for testing/debugging if gateway fails
             return response()->json(['message' => 'OTP Failed, fallback enabled', 'debug_otp' => $otp]);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'otp' => 'required|string'
        ]);

        $cachedOtp = \Illuminate\Support\Facades\Cache::get('otp_' . $request->phone_number);

        if ($cachedOtp && $cachedOtp == $request->otp) {
            \Illuminate\Support\Facades\Cache::forget('otp_' . $request->phone_number);
            return response()->json(['message' => 'Phone number verified']);
        }

        return response()->json(['message' => 'Invalid or expired OTP'], 400);
    }
}
