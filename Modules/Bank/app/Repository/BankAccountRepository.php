<?php

namespace Modules\Bank\Repository;

use Modules\Bank\Repository\Contracts\BankAccountRepositoryInterface;
use Modules\Base\Repository\BaseRepository;

class BankAccountRepository extends BaseRepository implements BankAccountRepositoryInterface
{
    public function decrementBalance(int $amount): BankAccountRepositoryInterface
    {
        $this->model->decrement('balance', $amount);

        $this->freshModel();

        return $this;
    }
}
