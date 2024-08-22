<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\OptionsResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class QuestionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'data' => $this->collection->map(function ($question) {
                return [
                    'id' => $question->id,
                    'question_text' => $question->question_text,
                    'type' => $question->type,
                    'created_at' => $question->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $question->updated_at->format('Y-m-d H:i:s'),
                    'options' => OptionsResource::collection($question->options),
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
