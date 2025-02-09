<?php

namespace Modules\Bank\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Bank\Enums\BankAccountStatus;
use Modules\Bank\Enums\BankAccountType;

class BankAccount extends Model
{
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
}
