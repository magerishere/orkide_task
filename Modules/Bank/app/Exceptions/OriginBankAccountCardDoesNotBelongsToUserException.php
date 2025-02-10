<?php

namespace Modules\Bank\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class OriginBankAccountCardDoesNotBelongsToUserException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message ?: __('bank::v1.errors.origin_bank_account_card_does_not_belongs_to_user'), Response::HTTP_FORBIDDEN, $previous);
    }
}
