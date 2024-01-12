<?php

namespace App\Responses;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class OKResponse
 */
class OKResponse extends JsonResponse
{
    /**
     * OKResponse constructor.
     * @param array $errors
     * @param array $headers
     */
    public function __construct(string $message = 'ok')
    {
        parent::__construct([
            'message' => $message
        ], 200);
    }
}
