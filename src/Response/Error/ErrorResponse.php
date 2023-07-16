<?php

namespace App\Response\Error;

class ErrorResponse
{
    public function __construct(
        private readonly string $error,
        private readonly mixed $context = null
    ) {
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @return mixed
     */
    public function getContext(): mixed
    {
        return $this->context;
    }
}