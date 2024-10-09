<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Book;

final class BookService
{
    /**
     * Обновляет счётчик символов для книги на основе содержания всех глав.
     *
     * @param Book $book
     *
     * @return void
     */
    public function updateCharacterCount(Book $book): void
    {
        $totalCharacters = $book->chapters()
            ->selectRaw('SUM(LENGTH(content)) as total')
            ->value('total');

        $book->character_count = $totalCharacters ?? 0;
        $book->save();
    }
}
