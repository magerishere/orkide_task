<?php

namespace Modules\Bank\Enums;


use Modules\Base\Traits\Enumable;

enum BankAccountStatus: string
{
    use Enumable;

    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public function enumsLang(): array
    {
        return __('bank::enums');
    }
}
