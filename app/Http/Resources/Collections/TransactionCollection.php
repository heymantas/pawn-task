<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'user_id' => $transaction->user_id,
                    'points' => $transaction->points,
                    'is_claimed' => $transaction->is_claimed,
                    'created_at' => $transaction->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $transaction->updated_at->format('Y-m-d H:i:s'),
                ];
            }),
        ];
    }

    public function withResponse($request, $response): void
    {
        /**
         * Not all prerequisites were met.
         */
        $response->setStatusCode(200, '');
    }

    public function with($request): array
    {
        return [
            'status' => 'success'
        ];
    }
}
