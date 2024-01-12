<?php

namespace App\Responses;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ForbiddenResponse
 */
class ForbiddenResponse extends JsonResponse
{
    /**
     * ForbiddenResponse constructor.
     * @param array $errors
     */
    public function __construct(string $message = 'Forbidden')
    {
        parent::__construct([
            'error' => $message
        ], 403);
    }
}
