<?php

use Modules\Bank\Enums\BankAccountStatus;
use Modules\Bank\Enums\BankAccountType;

return [
    BankAccountStatus::class => [
        BankAccountStatus::ACTIVE->name => 'فعال',
        BankAccountStatus::INACTIVE->name => 'غیر فعال'
    ],
    BankAccountType::class => [
        BankAccountType::DEPOSIT->name => 'سپرده',
        BankAccountType::FACILITY->name => 'تسهیلات'
    ],
];
