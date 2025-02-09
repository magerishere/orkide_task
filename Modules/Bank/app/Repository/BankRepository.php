<?php

namespace Modules\Bank\Repository;

use Modules\Bank\Repository\Contracts\BankRepositoryInterface;
use Modules\Base\Repository\BaseRepository;

class BankRepository extends BaseRepository implements BankRepositoryInterface
{
    public function findByPrefixCardNumber(string $prefix): BankRepositoryInterface
    {
        $this->where('prefix_card_number', $prefix)->first();
        return $this;
    }
}
