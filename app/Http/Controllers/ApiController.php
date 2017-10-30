<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ApiController extends Controller
{

    protected $statusCode = 200;

    public function respond($data, $header = [])
    {
        return Response::json($data, $this->getStatusCode(), $header);
    }

    public function respondCreated($message = 'Created.')
    {
        return $this->setStatusCode(SymfonyResponse::HTTP_CREATED)
            ->respond($message);
    }

    public function respondNoContent($message = 'No content.')
    {
        return $this->setStatusCode(SymfonyResponse::HTTP_NO_CONTENT)
            ->respond($message);
    }

    public function respondForbidden($message = 'Unauthorized!')
    {
        return $this->setStatusCode(SymfonyResponse::HTTP_FORBIDDEN)
            ->respondWithError($message);
    }

    public function respondNotFound($message = 'Not Found!')
    {
        return $this->setStatusCode(SymfonyResponse::HTTP_NOT_FOUND)
            ->respondWithError($message);
    }

    public function respondInternalError($message = 'Internal server error!')
    {
        return $this->setStatusCode(SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR)
            ->respondWithError($message);
    }

    public function respondWithError($message)
    {
        return $this->respond([
            'error' => [
                'message'     => $message,
                'status_code' => $this->getStatusCode()
            ]
        ]);
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param mixed $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }
}
