<?php

namespace Modules\Bank\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Modules\Bank\Database\Factories\BankAccountCardFactory;
use Modules\Bank\Enums\BankAccountCardStatus;
use Modules\Bank\Repository\Contracts\BankAccountRepositoryInterface;
use Modules\Bank\Repository\Contracts\BankRepositoryInterface;
use Modules\Transaction\Repository\Contracts\TransactionRepositoryInterface;
use Modules\User\Repository\Contracts\UserRepositoryInterface;

class BankAccountCard extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'number';

    protected $fillable = [
        'number',
        'bank_account_number',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => BankAccountCardStatus::class,
        ];
    }

    protected static function newFactory(): Factory
    {
        return BankAccountCardFactory::new();
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(app(BankAccountRepositoryInterface::class)->getModel(), 'bank_account_number');
    }

    public function bank(): HasOneThrough
    {
        return $this->hasOneThrough(
            app(BankRepositoryInterface::class)->getModel(),
            app(BankAccountRepositoryInterface::class)->getModel(),
            'number', 'code', 'bank_account_number', 'bank_code'
        );
    }

    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(
            app(UserRepositoryInterface::class)->getModel(),
            app(BankAccountRepositoryInterface::class)->getModel(),
            'number', 'mobile', 'bank_account_number', 'user_mobile'
        );
    }

    public function sentTransactions(): HasMany
    {
        return $this->hasMany(
            app(TransactionRepositoryInterface::class)->getModel(),
            'from_bank_account_card_number',
        );
    }

    public function latestSentTransactions(): HasMany
    {
        return $this->sentTransactions()->latest()->take(10);
    }

    public function receivedTransactions(): HasMany
    {
        return $this->hasMany(
            app(TransactionRepositoryInterface::class)->getModel(),
            'to_bank_account_card_number',
        );
    }

    public function latestReceivedTransactions(): HasMany
    {
        return $this->receivedTransactions()->latest()->take(10);
    }

    public function allTransactions(): Attribute
    {
        $this->loadMissing([
            'sentTransactions',
            'receivedTransactions',
        ]);
        return Attribute::get(
            fn() => $this->sentTransactions->merge(
                $this->receivedTransactions,
            )
        );
    }

    public function allLatestTransactions(): Attribute
    {
        $this->loadMissing([
            'latestSentTransactions',
            'latestReceivedTransactions',
        ]);
        return Attribute::get(
            fn() => $this->latestSentTransactions->merge(
                $this->latestReceivedTransactions,
            )
        );
    }
}
