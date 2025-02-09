<?php

namespace Modules\Bank\Models;

use Illuminate\Database\Eloquent\Model;

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
}
