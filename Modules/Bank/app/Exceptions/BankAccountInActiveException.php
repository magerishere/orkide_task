<?php

namespace Modules\Bank\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class BankAccountInActiveException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message ?: __('bank::v1.errors.bank_account_in_active', [
            'account_number' => ''
        ]), Response::HTTP_FORBIDDEN, $previous);
    }
}
