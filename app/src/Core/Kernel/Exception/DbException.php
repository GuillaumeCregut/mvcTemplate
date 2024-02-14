<?php

namespace Editiel98\Kernel\Exception;

use Exception;
use Throwable;

class DbException extends Exception
{
    private int $dbCode;
    private string $dBMessage;

    public function __construct(string $message, int $dbCodeError, string $dbMessage, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->dbCode = $dbCodeError;
        $this->dBMessage = $dbMessage;
    }

    public function __toString(): string
    {
        return __CLASS__ . ":[$this->code]: {$this->message}\n";
    }

    public function getdbMessage(): string
    {
        return $this->dBMessage;
    }

    public function getDbCode(): int
    {
        return $this->dbCode;
    }
}
