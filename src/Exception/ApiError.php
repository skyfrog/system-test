<?php

namespace App\Exception;

class ApiError extends \Exception
{
    public function __construct(string $message, private readonly mixed $context = null)
    {
        parent::__construct($message);
    }

    /**
     * @return mixed
     */
    public function getContext(): mixed
    {
        return $this->context;
    }
}