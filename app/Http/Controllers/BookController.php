<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * Контроллер для управления книгами.
 */
final class BookController extends Controller
{
    /**
     * Получить список книг с указанием автора.
     *
     * @return AnonymousResourceCollection Коллекция ресурсов книг.
     */
    public function getList(): AnonymousResourceCollection
    {
        $books = Book::query()
            ->with('author')
            ->paginate(10);

        return BookResource::collection($books);
    }

    /**
     * Получить книгу по её ID.
     *
     * @param int $id Идентификатор книги.
     *
     * @return BookResource Ресурс книги с автором и главами.
     */
    public function findByID(int $id): BookResource
    {
        $book = Book::query()
            ->with('author', 'chapters')
            ->where('id', $id)
            ->first();

        return new BookResource($book);
    }

    /**
     * Создаёт новую книгу.
     *
     * @param StoreBookRequest $request Запрос, содержащий данные для создания книги.
     *
     * @return BookResource Ресурс созданной книги.
     * @throws ApiException В случае ошибки создания книги.
     */
    public function create(StoreBookRequest $request): BookResource
    {
        try {
            $fields = $request->all();

            $book = Book::query()
                ->create([
                    'author_id' => $fields['author_id'],
                    'title' => $fields['title'],
                    'annotation' => $fields['annotation'],
                    'publication_date' => $fields['publication_date'],
                ]);

            return new BookResource($book);
        } catch (\Exception $e) {
            throw new ApiException('Ошибка при создании книги: '.$e->getMessage(), 500);
        }
    }

    /**
     * Обновляет данные книги.
     *
     * @param UpdateBookRequest $request Запрос, содержащий обновлённые данные книги.
     * @param int $id Идентификатор книги для обновления.
     *
     * @return BookResource Ресурс обновлённой книги.
     * @throws ApiException В случае ошибки обновления книги.
     */
    public function update(UpdateBookRequest $request, int $id): BookResource
    {
        try {
            $book = Book::query()->findOrFail($id);
            $fields = $request->all();

            $book->update([
                'author_id' => $fields['author_id'],
                'title' => $fields['title'],
                'annotation' => $fields['annotation'],
                'publication_date' => $fields['publication_date'],
            ]);

            return new BookResource($book);
        } catch (\Exception $e) {
            throw new ApiException('Ошибка при обновлении книги: '.$e->getMessage(), 500);
        }
    }

    /**
     * Удаляет книгу.
     *
     * @param int $id Идентификатор главы.
     *
     * @return Response
     * @throws ApiException
     */
    public function delete(int $id): Response
    {
        try {
            $chapter = Book::query()->findOrFail($id);
            $chapter->delete();

            return response()->noContent();
        } catch (\Exception $e) {
            throw new ApiException('Ошибка при удалении книги: '.$e->getMessage(), 500);
        }
    }
}
