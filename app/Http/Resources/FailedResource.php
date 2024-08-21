<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FailedResource extends JsonResource
{
    protected $statusCode;
    protected $message;

    public function __construct($statusCode, $message)
    {
        $this->statusCode = $statusCode;
        $this->message = $message;
    }

    public function toArray($request)
    {
        return [

        ];
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode($this->statusCode);
    }

    public function with($request)
    {
        return [
            'status' => 'error',
            'message' => $this->message,
        ];
    }

}
