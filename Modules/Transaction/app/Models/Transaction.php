<?php

namespace Modules\Transaction\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Transaction\Enums\TransactionStatus;

class Transaction extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'ref_number';

    protected $fillable = [
        'ref_number',
        'from_bank_account_card_number',
        'to_bank_account_card_number',
        'status',
        'amount'
    ];

    protected function casts(): array
    {
        return [
            'status' => TransactionStatus::class,
        ];
    }
}
