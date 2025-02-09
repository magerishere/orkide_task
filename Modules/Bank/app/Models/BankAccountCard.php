<?php

namespace Modules\Bank\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Bank\Enums\BankAccountCardStatus;

class BankAccountCard extends Model
{
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
}
