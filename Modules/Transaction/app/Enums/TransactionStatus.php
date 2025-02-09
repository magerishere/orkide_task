<?php

namespace Modules\Transaction\Enums;


use Modules\Base\Traits\Enumable;

enum TransactionStatus: string
{
    use Enumable;

    case COMPLETED = 'completed';
    case CANCEL = 'cancel';
    case PENDING = 'pending';

    public function enumsLang(): array
    {
        return __('transaction::enums');
    }
}
