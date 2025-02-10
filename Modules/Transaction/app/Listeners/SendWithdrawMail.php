<?php

namespace Modules\Transaction\app\Listeners;

use Illuminate\Support\Facades\Mail;
use Modules\Transaction\app\Events\CardToCardTransaction;
use Modules\Transaction\app\Mail\WithdrawCardToCard;

class SendWithdrawMail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CardToCardTransaction $event): void
    {
        $transaction = $event->transaction;
        Mail::to($transaction->sender)->send(new WithdrawCardToCard($transaction));
    }
}
