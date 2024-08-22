<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserWalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'balance' => $this->balance,
            'unclaimedTransactionsCount' => $this->unclaimed_transactions_count,
            'pendingBalance' => $this->pending_balance,
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
