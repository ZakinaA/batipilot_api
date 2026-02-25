<?php

namespace App\Exception;

class ApiValidationException extends ApiException
{
    public static function fromErrors(array $errors): self
    {
        return new self(
            message: 'Validation error',
            code: 'VALIDATION_ERROR',
            status: 400,
            details: ['errors' => $errors]
        );
    }
}