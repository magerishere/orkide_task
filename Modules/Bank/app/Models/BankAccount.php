<?php

namespace Modules\Bank\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Bank\Database\Factories\BankAccountFactory;
use Modules\Bank\Enums\BankAccountStatus;
use Modules\Bank\Enums\BankAccountType;

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
}
