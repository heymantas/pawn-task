<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessResource extends JsonResource
{

    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function toArray($request): array
    {
        return [

        ];
    }

    public function withResponse($request, $response): void
    {
        $response->setStatusCode(200);
    }

    public function with($request): array
    {
        return [
            'status' => 'success',
            'message' => $this->message,
        ];
    }
}
