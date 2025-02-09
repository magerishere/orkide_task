<?php

namespace Modules\Bank\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Bank\Enums\CountryCode;

class Bank extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'code';

    protected $fillable = [
        'code',
        'country_code',
        'prefix_structure',
        'prefix_card_number',
        'name',
        'name_fa',
    ];

    protected function casts(): array
    {
        return [
            'country_code' => CountryCode::class,
        ];
    }
}
