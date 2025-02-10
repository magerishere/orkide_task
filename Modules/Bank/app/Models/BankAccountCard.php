<?php

namespace Modules\Bank\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Modules\Bank\Database\Factories\BankAccountCardFactory;
use Modules\Bank\Enums\BankAccountCardStatus;
use Modules\Bank\Repository\Contracts\BankAccountRepositoryInterface;
use Modules\Bank\Repository\Contracts\BankRepositoryInterface;
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
}
