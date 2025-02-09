<?php

namespace Modules\Transaction\Repository\Contracts;

use Modules\Base\Repository\Contracts\BaseRepositoryInterface;

interface TransactionRepositoryInterface extends BaseRepositoryInterface
{
    public function newRefNumber(): string;
}
