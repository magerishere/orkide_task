<?php

namespace Modules\Bank\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class UserHasNoBankAccountException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message ?: __('bank::v1.errors.user_has_no_bank_account'), Response::HTTP_FORBIDDEN, $previous);
    }
}
