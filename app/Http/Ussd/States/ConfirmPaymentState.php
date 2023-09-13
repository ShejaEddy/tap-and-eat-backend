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
        $this->menu->text('END Please, confirm payment by dialing *182*7*1# on your phone number for a transaction of an amount of ' . number_format($amount) . ' RWF');
        $this->pay($amount, $phoneNumber);
    }

    protected function afterRendering(string $argument): void
    {
        //
    }
}
