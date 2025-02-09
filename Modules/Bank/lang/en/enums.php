<?php

use Modules\Bank\Enums\BankAccountStatus;
use Modules\Bank\Enums\BankAccountType;

return [
    BankAccountStatus::class => [
        BankAccountStatus::ACTIVE->name => 'Active',
        BankAccountStatus::INACTIVE->name => 'InActive'
    ],
    BankAccountType::class => [
        BankAccountType::DEPOSIT->name => 'Deposit',
        BankAccountType::FACILITY->name => 'Facility'
    ],
];
