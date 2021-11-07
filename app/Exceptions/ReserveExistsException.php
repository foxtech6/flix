<?php

namespace App\Exceptions;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ReserveExistsException extends HttpResponseException
{
    /**
     * @param null $message
     * @param null $code
     */
    public function __construct($message = null, $code = null)
    {
        parent::__construct(new JsonResponse(
            ['error' => $message ?? 'Reservation already exists'],
            $code ?? Response::HTTP_BAD_REQUEST,
        ));
    }
}
