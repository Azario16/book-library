<?php

namespace App\Service;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface as ValidationErrors;

class ValidationErrorService
{
    public function checkForValidationErrors(ValidationErrors $validationErrors)
    {
        if ($validationErrors->count()) {
            $errors = [];
            foreach ($validationErrors as $error) {
                $errors[] = [
                    'propertyPath' => $error->getPropertyPath(),
                    'message' => $error->getMessage(),
                ];
            }
            throw new BadRequestHttpException(json_encode($errors));
        }
    }
}
