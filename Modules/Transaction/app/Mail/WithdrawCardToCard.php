<?php

namespace Modules\Transaction\app\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Modules\Transaction\Models\Transaction;

class WithdrawCardToCard extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Transaction $transaction)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('transaction::v1.mail.with_draw_card_to_card.subject', ['bank_name' => $this->transaction->fromBankAccountCard->bank->fullName]),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $transaction = $this->transaction;

        return new Content(
            markdown: 'transaction::mail.v1.with_draw_card_to_card',
            with: [
                'amount' => number_format($transaction->amount),
                'cardNumber' => $transaction->from_bank_account_card_number,
                'bankName' => $transaction->fromBankAccountCard->bank->fullName,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
