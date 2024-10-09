<?php

namespace App\Observers;

use App\Models\Chapter;
use App\Services\BookService;

final class ChapterObserver
{
    protected BookService $bookService;

    /**
     * @param  BookService  $bookService
     */
    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
     * Обработать событие "создание" для модели Chapter.
     *
     * @param  Chapter  $chapter
     *
     * @return void
     */
    public function created(Chapter $chapter): void
    {
        $this->bookService->updateCharacterCount($chapter->book);
    }

    /**
     * Обработать событие "обновление" для модели Chapter.
     *
     * @param  Chapter  $chapter
     *
     * @return void
     */
    public function updated(Chapter $chapter): void
    {
        $this->bookService->updateCharacterCount($chapter->book);
    }

    /**
     * Обработать событие "обновление" для модели Chapter.
     *
     * @param  Chapter  $chapter
     *
     * @return void
     */
    public function deleted(Chapter $chapter): void
    {
        $this->bookService->updateCharacterCount($chapter->book);
    }
}
