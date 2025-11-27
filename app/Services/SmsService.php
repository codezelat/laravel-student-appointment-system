<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $apiUrl;
    protected $username;
    protected $password;
    protected $source;

    public function __construct()
    {
        $this->apiUrl = config('sms.api_url');
        $this->username = config('sms.username');
        $this->password = config('sms.password');
        $this->source = config('sms.source');
    }

    /**
     * Send SMS notification
     *
     * @param string $phoneNumber
     * @param string $message
     * @return bool
     */
    public function sendSms($phoneNumber, $message)
    {
        try {
            // For local testing, just log the SMS instead of sending
            if (config('app.env') === 'local' && config('sms.test_mode', true)) {
                Log::info('SMS Test Mode - Message would be sent:', [
                    'to' => $phoneNumber,
                    'message' => $message,
                    'timestamp' => now()->toDateTimeString()
                ]);
                
                return true;
            }

            // Send actual SMS with SSL verification disabled for local development
            $httpClient = Http::asForm();
            
            // Disable SSL verification for local development
            if (config('app.env') === 'local') {
                $httpClient = $httpClient->withOptions([
                    'verify' => false
                ]);
            }
            
            $response = $httpClient->get($this->apiUrl, [
                'username' => $this->username,
                'password' => $this->password,
                'src' => $this->source,
                'dst' => $phoneNumber,
                'msg' => $message,
                'dr' => 1
            ]);

            if ($response->successful()) {
                Log::info('SMS sent successfully', [
                    'to' => $phoneNumber,
                    'response' => $response->body()
                ]);
                return true;
            }

            Log::error('SMS sending failed', [
                'to' => $phoneNumber,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('SMS sending exception', [
                'to' => $phoneNumber,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Send appointment confirmation SMS
     *
     * @param string $phoneNumber
     * @param string $studentName
     * @param string $date
     * @param string $timeSlot
     * @param string $address
     * @return bool
     */
    public function sendAppointmentConfirmation($phoneNumber, $studentName, $date, $timeSlot, $address)
    {
        $message = "Dear {$studentName}, your appointment at World Trade Center, Level 26, East Tower, Colombo 01 has been confirmed for {$date} at {$timeSlot}. Please arrive 10 minutes early. Thank you!";

        return $this->sendSms($phoneNumber, $message);
    }
}
