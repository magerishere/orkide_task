<?php

namespace Modules\Bank\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class BankAccountCardInActiveException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message ?: __('bank::v1.errors.bank_account_card_in_active', [
            'card_number' => ''
        ]), Response::HTTP_FORBIDDEN, $previous);
    }
}
