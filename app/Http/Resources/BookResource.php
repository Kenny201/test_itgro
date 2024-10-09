<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class BookResource extends JsonResource
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
            'author_id' => $this->author_id,
            'title' => $this->title,
            'annotation' => $this->annotation,
            'publication_date' => $this->publication_date,
            'character_count' => $this->character_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'author' => new AuthorResource($this->whenLoaded('author')),
            'chapters' => ChapterResource::collection($this->whenLoaded('chapters')),
        ];
    }
}
