<?php

namespace App\Http\Ussd\States;

use App\Http\Traits\UtilityTrait;
use Sparors\Ussd\State;

class ConfirmPaymentState extends State
{
    use UtilityTrait;

    protected function beforeRendering(): void
    {
        $amount = $this->record->get('topup_amount');

        $phoneNumber = $this->record->phoneNumber;
        $ok = $this->pay($amount, $phoneNumber);

        if($ok)
            $this->menu->text('END Please, confirm payment by dialing *182*7*1# on your phone number for a transaction of an amount of ' . number_format($amount) . ' RWF');
        else
            $this->menu->text('END Sorry, your transaction could not be processed. Please try again later');
    }

    protected function afterRendering(string $argument): void
    {
        //
    }
}
