<?php

namespace Milebits\Eloquent\Filters;

use Exception;
use JetBrains\PhpStorm\Pure;
use Throwable;

class UserNullException extends Exception
{
    #[Pure]
    public function __construct(Throwable $previous = null)
    {
        parent::__construct('Canceller user cannot be null !', 403, $previous);
    }
}
