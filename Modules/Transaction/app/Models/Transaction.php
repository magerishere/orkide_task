<?php

namespace Modules\Transaction\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Bank\Repository\Contracts\BankAccountCardRepositoryInterface;
use Modules\Base\Traits\HasDates;
use Modules\Transaction\Enums\TransactionStatus;

class Transaction extends Model
{
    use HasDates;

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

    public function fromBankAccountCard(): BelongsTo
    {
        return $this->belongsTo(app(BankAccountCardRepositoryInterface::class)->getModel(), 'from_bank_account_card_number');
    }

    public function toBankAccountCard(): BelongsTo
    {
        return $this->belongsTo(app(BankAccountCardRepositoryInterface::class)->getModel(), 'to_bank_account_card_number');
    }

    public function sender(): Attribute
    {
        return Attribute::get(
            fn() => $this->fromBankAccountCard->user,
        );
    }

    public function receiver(): Attribute
    {
        return Attribute::get(
            fn() => $this->toBankAccountCard->user,
        );
    }

    public function amountText(): Attribute
    {
        return Attribute::get(
            fn() => number_format($this->amount),
        );
    }
}
