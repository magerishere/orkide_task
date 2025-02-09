<?php

use Modules\Transaction\Enums\TransactionStatus;

return [
    TransactionStatus::class => [
        TransactionStatus::COMPLETED->name => 'Completed',
        TransactionStatus::CANCEL->name => 'Cancelled',
        TransactionStatus::PENDING->name => 'Pending',
    ],
];
