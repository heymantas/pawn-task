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

    public function toArray($request): array
    {
        return [

        ];
    }

    public function withResponse($request, $response): void
    {
        $response->setStatusCode($this->statusCode);
    }

    public function with($request): array
    {
        return [
            'status' => 'error',
            'message' => $this->message,
        ];
    }

}
