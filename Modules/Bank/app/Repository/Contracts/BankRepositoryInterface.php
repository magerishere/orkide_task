<?php

namespace Modules\Bank\Repository\Contracts;

use Modules\Base\Repository\Contracts\BaseRepositoryInterface;

interface BankRepositoryInterface extends BaseRepositoryInterface
{
    public function findByPrefixCardNumber(string $prefix): self;
}
