<?php

namespace App\Response\Error;

class ErrorResponse
{
    public function __construct(
        private readonly string $error
    )
    {
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }
}