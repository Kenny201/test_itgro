<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Chapter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chapter>
 */
class ChapterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'book_id' => Book::factory(),
            'title' => $this->faker->sentence(4),
            'content' => $this->faker->text(500),
        ];
    }

    public function configure(): ChapterFactory|Factory
    {
        return $this->afterCreating(function (Chapter $chapter) {
            $book = $chapter->book;

            $totalCharacters = $book->chapters()
                ->selectRaw('SUM(LENGTH(content)) as total')
                ->value('total');

            $book->character_count = $totalCharacters ?? 0;
            $book->save();
        });
    }
}
