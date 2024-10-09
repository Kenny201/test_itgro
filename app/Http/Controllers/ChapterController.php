<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\StoreChapterRequest;
use App\Http\Requests\UpdateChapterRequest;
use App\Http\Resources\ChapterResource;
use App\Models\Chapter;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * Контроллер для управления главами книг.
 */
final class ChapterController extends Controller
{
    /**
     * Получить список глав для указанной книги.
     *
     * @param int $bookID Идентификатор книги, для которой отображаются главы.
     *
     * @return AnonymousResourceCollection Коллекция ресурсов глав.
     */
    public function getList(int $bookID): AnonymousResourceCollection
    {
        $chapters = Chapter::query()
            ->where('book_id', $bookID)->get();

        return ChapterResource::collection($chapters);
    }

    /**
     * Получить одну главу по её ID для указанной книги.
     *
     * @param int $bookID Идентификатор книги.
     * @param int $id Идентификатор главы.
     *
     * @return ChapterResource Ресурс главы.
     */
    public function findByID(int $bookID, int $id): ChapterResource
    {
        $chapter = Chapter::query()
            ->where('book_id', $bookID)
            ->where("id", $id)
            ->first();

        return new ChapterResource($chapter);
    }

    /**
     * Создаёт новую главу для указанной книги.
     *
     * @param StoreChapterRequest $request Запрос, содержащий данные для создания главы.
     * @param int $bookId Идентификатор книги, к которой относится новая глава.
     *
     * @return ChapterResource Ресурс созданной главы.
     * @throws ApiException В случае ошибки создания главы.
     */
    public function create(StoreChapterRequest $request, int $bookId): ChapterResource
    {
        try {
            $fields = $request->all();

            $chapter = Chapter::query()
                ->create([
                    'book_id' => $bookId,
                    'title' => $fields['title'],
                    'content' => $fields['content'],
                ]);

            return new ChapterResource($chapter);
        } catch (\Exception $e) {
            throw new ApiException('Ошибка при создании главы: '.$e->getMessage(), 500);
        }
    }

    /**
     * Обновляет данные главы для указанной книги.
     *
     * @param UpdateChapterRequest $request Запрос, содержащий обновлённые данные главы.
     * @param int $bookId Идентификатор книги, к которой относится глава.
     * @param int $id Идентификатор главы для обновления.
     *
     * @return ChapterResource Ресурс обновлённой главы.
     * @throws ApiException В случае ошибки обновления главы.
     */
    public function update(UpdateChapterRequest $request, int $bookId, int $id): ChapterResource
    {
        try {
            $fields = $request->all();

            $chapter = Chapter::query()->where("book_id", $bookId)->findOrFail($id);
            $chapter
                ->update([
                    'title' => $fields['title'],
                    'content' => $fields['content'],
                ]);

            return new ChapterResource($chapter);
        } catch (\Exception $e) {
            throw new ApiException('Ошибка при обновлении главы: '.$e->getMessage(), 500);
        }
    }

    /**
     * Удаляет данные главы для указанной книги.
     *
     * @param int $bookId Идентификатор книги, к которой относится глава.
     * @param int $id Идентификатор главы для обновления.
     *
     * @return Response
     * @throws ApiException
     */
    public function delete(int $bookId, int $id): Response
    {
        try {
            $chapter = Chapter::query()->where('book_id', $bookId)->findOrFail($id);
            $chapter->delete();

            return response()->noContent();
        } catch (\Exception $e) {
            throw new ApiException('Ошибка при удалении главы: '.$e->getMessage(), 500);
        }
    }
}
