<?php

namespace Modules\Transaction\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'ref_number';
}
