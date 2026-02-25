<?php

namespace App\Service\Api;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationErrorMapper
{
    public function map(ConstraintViolationListInterface $violations): array
    {
        $out = [];
        foreach ($violations as $v) {
            $out[] = [
                'path' => $v->getPropertyPath(),
                'message' => $v->getMessage(),
            ];
        }
        return $out;
    }
}