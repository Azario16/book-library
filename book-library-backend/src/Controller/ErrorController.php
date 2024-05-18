<?php

/* 
    Второй вариант обработки исключений и отображения их в виде json 
*/

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class ErrorController extends AbstractController
{
    public function show(Exception $exception ): JsonResponse
    {
        return $this->json([
            'message' => $exception->getMessage(),
            'code' => $exception->getCode()
        ]);
    }
}