<?php

namespace Modules\Bank\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Bank\Database\Factories\BankAccountFactory;
use Modules\Bank\Enums\BankAccountStatus;
use Modules\Bank\Enums\BankAccountType;
use Modules\Bank\Repository\Contracts\BankAccountCardRepositoryInterface;
use Modules\Bank\Repository\Contracts\BankRepositoryInterface;
use Modules\User\Repository\Contracts\UserRepositoryInterface;

class BankAccount extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'number';

    protected $fillable = [
        'number',
        'bank_code',
        'user_mobile',
        'status',
        'type',
        'balance',
    ];

    protected function casts(): array
    {
        return [
            'status' => BankAccountStatus::class,
            'type' => BankAccountType::class,
        ];
    }

    protected static function newFactory(): Factory
    {
        return BankAccountFactory::new();
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(app(BankRepositoryInterface::class)->getModel());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(app(UserRepositoryInterface::class), 'user_mobile', 'mobile');
    }

    public function cards(): HasMany
    {
        return $this->hasMany(app(BankAccountCardRepositoryInterface::class)->getModel());
    }

    public function cardsFromNumber(string $cardNumber): HasOne
    {
        return $this->cards()->where('number', $cardNumber)->one();
    }

    public function checkBalance(int $amount): bool
    {
        return intval($this->balance) >= $amount;
    }
}
