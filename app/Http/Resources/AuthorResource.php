<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AuthorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'info' => $this->info,
            'birthdate' => $this->birthdate,
            'books_count' => $this->when(isset($this->books_count), $this->books_count),
            'books' => BookResource::collection($this->whenLoaded('books')),
        ];
    }
}
