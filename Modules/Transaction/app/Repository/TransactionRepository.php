<?php

namespace Modules\Transaction\Repository;

use Modules\Base\Repository\BaseRepository;
use Modules\Transaction\Repository\Contracts\TransactionRepositoryInterface;

class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    public function newRefNumber(): string
    {
        $refNumber = randomDigits(length: 10);
        if ($this->freshQuery()->where('ref_number', $refNumber)->exists()) {
            return $this->newRefNumber();
        }

        return $refNumber;
    }
}
