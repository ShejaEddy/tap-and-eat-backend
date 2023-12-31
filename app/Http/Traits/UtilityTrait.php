<?php


namespace App\Http\Traits;


use App\Student;
use App\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait UtilityTrait
{

    public function sendSMS($phone, $sms)
    {
        $headers = [
            "Content-Type" => "application/json",
        ];
        $response = Http::withHeaders($headers)->post(env("SMS_URL"), [
            "client" => "zillionizer",
            "kuri" => "25" . $phone,
            "ubutumwa" => $sms,
            "pin" => "9u7b0b",
            "action" => "urn:ksend",
            "msgid" => "123",
            "ohereza" => env("SMS_FROM"),
            "receivedlr" => 0,
            "callurl" => "",
            "messagetype" => "flash"
        ]);
        Log::info("message sent to " . $phone . " with response " . $response->body());
        if ($response->status() != 200) {
            return false;
        }
        $body = json_decode($response->body());
        return $body && $body->message == "0: Accepted for delivery";
    }

    public function momoPay($tx_ref, $amount, $phoneNumber, $trx)
    {
        $data = [
            "amount" => +$amount,
            "number" => $phoneNumber,
        ];


        Log::info("MOMO PAYMENT REQUEST: ", ['data' => $data, 'tx_ref' => $tx_ref, 'client_id' => env("OPAY_CLIENT_ID"), 'client_secret' => env("OPAY_CLIENT_SECRET")]);

        $auth_url = "https://payments.paypack.rw/api/auth/agents/authorize";
        $auth_result = Http::withHeaders(
            [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        )->post($auth_url, [
                "client_id" => env("OPAY_CLIENT_ID"),
                "client_secret" => env("OPAY_CLIENT_SECRET")
            ]);

        Log::info("MOMO PAYMENT AUTH RESPONSE: ", ['result' => $auth_result->body(), 'status' => $auth_result->status()]);

        $access_token = json_decode($auth_result->body())->access;

        $URL = "https://payments.paypack.rw/api/transactions/cashin";
        $result = Http::withHeaders(
            [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Idempotency-Key' => $tx_ref,
                'Authorization' => 'Bearer ' . $access_token,
                'X-Webhook-Mode' => 'production'
            ]
        )->post($URL, $data);

        Log::info("MOMO PAYMENT RESPONSE: ", ['result' => $result->body(), 'status' => $result->status()]);

        if ($result->status() != 200) {
            return false;
        }

        $body = $result->body() ? json_decode($result->body()) : null;
        $refId = $body ? $body->ref : null;
        $trx->transaction_id = $refId;
        $trx->save();

        // check if trx body is pending
        if ($body && $body->status == "pending") {
            return true;
        }

        return false;
    }

    public function pay($amount, $phoneNumber)
    {
        $transactionId = uniqid();
        $student = Student::where('phoneNumber', $phoneNumber)->first();

        $trx = Transaction::create(
            [
                "phone_number" => $phoneNumber,
                "transaction_id" => $transactionId,
                "amount" => $amount,
                "student_id" => $student->id
            ]
        );

        $ok = $this->momoPay($transactionId, $amount, $phoneNumber, $trx);
        if (!$ok) {
            $trx->status = "FAILED";
            $trx->save();
            return false;
        }
        return true;
    }

    public function verifyStudent($phoneNumber, $pin)
    {
        $student = Student::where('phoneNumber', $phoneNumber)->first();
        $pinValid = $pin == $student->pin;
        return $pinValid;
    }

    // function converts object into query string
    public function toQueryString($obj)
    {
        $query = http_build_query($obj);
        return $query;
    }
}
