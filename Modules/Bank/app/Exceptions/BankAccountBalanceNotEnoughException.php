<?php

namespace Modules\Bank\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class BankAccountBalanceNotEnoughException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message ?: __('bank::v1.errors.bank_account_balance_not_enough'), Response::HTTP_FORBIDDEN, $previous);
    }
}
