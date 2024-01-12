<?php

namespace App\Responses;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class InternalServerErrorResponse
 */
class InternalServerErrorResponse extends JsonResponse
{
    /**
     * InternalServerErrorResponse constructor.
     * @param string[] $errors
     */
    public function __construct(array $errors)
    {
        parent::__construct([
            'errors' => $errors
        ], 500);
    }
}
