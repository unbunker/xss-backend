<?php

namespace App\Responses;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class UnauthorizedResponse
 */
class UnauthorizedResponse extends JsonResponse
{
    /**
     * UnauthorizedResponse constructor.
     * @param array $errors
     * @param array $headers
     */
    public function __construct(string $message = 'You are not authorized')
    {
        parent::__construct([
            'error' => $message
        ], 401);
    }
}
