<?php

namespace Modules\Bank\Repository\Contracts;

use Modules\Base\Repository\Contracts\BaseRepositoryInterface;

interface BankAccountRepositoryInterface extends BaseRepositoryInterface
{
    public function decrementBalance(int $amount): self;
}
