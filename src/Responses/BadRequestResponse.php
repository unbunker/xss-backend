<?php

namespace App\Responses;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class BadRequestRespones
 */
class BadRequestResponse extends JsonResponse
{
    /**
     * BadRequestResponse constructor.
     * @param string[] $errors
     */
    public function __construct(array $errors)
    {
        parent::__construct([
            'errors' => $errors
        ], 400);
    }
}
