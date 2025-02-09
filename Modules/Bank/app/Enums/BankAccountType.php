<?php

namespace Modules\Bank\Enums;


use Modules\Base\Traits\Enumable;

enum BankAccountType: int
{
    use Enumable;

    case DEPOSIT = 0;
    case FACILITY = 1;

    public function enumsLang(): array
    {
        return __('bank::enums');
    }
}
