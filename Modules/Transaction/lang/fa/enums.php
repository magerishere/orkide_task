<?php

use Modules\Transaction\Enums\TransactionStatus;

return [
    TransactionStatus::class => [
        TransactionStatus::COMPLETED->name => 'تکمیل شده',
        TransactionStatus::CANCEL->name => 'لغو شده',
        TransactionStatus::PENDING->name => 'در انتظار تایید'
    ],
];
