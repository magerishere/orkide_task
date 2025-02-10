<?php

namespace Modules\Transaction\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(fn($transaction) => [
            'ref_number' => $transaction->ref_number,
            'from_bank_account_card_number' => $transaction->from_bank_account_card_number,
            'to_bank_account_card_number' => $transaction->to_bank_account_card_number,
            'status' => $transaction->status,
            'status_label' => $transaction->status->label(),
            'amount' => $transaction->amount,
            'amount_text' => $transaction->amountText,
            'created_at_text' => $transaction->createdAtText,
            'updated_at_text' => $transaction->updatedAtText,
        ])->toArray();
    }
}
