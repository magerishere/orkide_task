<?php

namespace Modules\Bank\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccountCard extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'number';
}
