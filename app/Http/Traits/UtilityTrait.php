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
        if($response->status() != 200){
            return false;
        }
        $body = json_decode($response->body());
        return $body && $body->message == "0: Accepted for delivery";
    }

    public function momoPay($tx_ref, $amount, $phoneNumber)
    {
        $URL = "https://opay-api.oltranz.com/opay/paymentrequest";
        $result = Http::post($URL, [
            "telephoneNumber" => "25" . $phoneNumber,
            "amount" => $amount,
            "organizationId" => env("OPAY_ORGANIZATION_ID"),
            "description" => "Payment",
            "callbackUrl" => env("BACKEND_HTTPS_URL") . "/api/opay/payment-response",
            "transactionId" => $tx_ref
        ]);
        Log::info("MOMO PAYMENT RESPONSE: " . $result->body(), ['result' => $result, 'orgId' => env("OPAY_ORGANIZATION_ID")]);

        // check if request was successful
        if ($result->status() != 200) {
            return false;
        }
        $body = json_decode($result->body());
        if ($body->status == "FAILED") {
            return false;
        }

        return true;
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

        $ok = $this->momoPay($transactionId, $amount, $phoneNumber);
        if (!$ok) {
            $trx->status = "FAILED";
            $trx->save();
            return false;
        }
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
