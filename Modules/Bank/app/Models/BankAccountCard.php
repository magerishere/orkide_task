<?php

namespace Modules\Bank\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Bank\Database\Factories\BankAccountCardFactory;
use Modules\Bank\Enums\BankAccountCardStatus;

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
}
