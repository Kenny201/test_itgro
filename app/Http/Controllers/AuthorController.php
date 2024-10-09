<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\AuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * Контроллер для управления авторами.
 */
final class AuthorController extends Controller
{
    /**
     * Получить список авторов с количеством их книг.
     *
     * @return AnonymousResourceCollection Коллекция ресурсов авторов.
     */
    public function getList(): AnonymousResourceCollection
    {
        $authors = Author::query()
            ->withCount('books')
            ->orderBy('books_count', 'desc')
            ->paginate(15);

        return AuthorResource::collection($authors);
    }

    /**
     * Получить автора по его ID.
     *
     * @param int $id Идентификатор автора.
     *
     * @return AuthorResource Ресурс автора с его книгами.
     */
    public function findById(int $id): AuthorResource
    {
        $author = Author::query()
            ->with('books')
            ->where('id', $id)
            ->first();

        return new AuthorResource($author);
    }

    /**
     * Создать нового автора.
     *
     * @param AuthorRequest $request Запрос, содержащий данные для создания автора.
     *
     * @return AuthorResource Ресурс созданного автора.
     * @throws ApiException В случае ошибки создания автора.
     */
    public function create(AuthorRequest $request): AuthorResource
    {
        try {
            $fields = $request->all();

            $author = Author::query()
                ->create([
                    'name' => $fields['name'],
                    'info' => $fields['info'],
                    'birthdate' => $fields['birthdate'],
                ]);
            return new AuthorResource($author);
        } catch (\Exception $e) {
            throw new ApiException('Ошибка при создании автора: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Обновить данные автора.
     *
     * @param AuthorRequest $request Запрос, содержащий обновлённые данные автора.
     * @param int $id Идентификатор автора для обновления.
     *
     * @return AuthorResource Ресурс обновлённого автора.
     * @throws ApiException В случае ошибки обновления автора.
     */
    public function update(AuthorRequest $request, int $id): AuthorResource
    {
        try {
            $author = Author::query()->findOrFail($id);

            $fields = $request->all();

            $author
                ->update([
                    'name' => $fields['name'],
                    'info' => $fields['info'],
                    'birthdate' => $fields['birthdate'],
                ]);

            return new AuthorResource($author);
        } catch (\Exception $e) {
            throw new ApiException('Ошибка при обновлении автора: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Удалить автора.
     *
     * @param int $id Идентификатор автора.
     *
     * @return Response
     * @throws ApiException
     */
    public function delete(int $id): Response
    {
        try {
            $chapter = Author::query()->findOrFail($id);
            $chapter->delete();

            return response()->noContent();
        } catch (\Exception $e) {
            throw new ApiException('Ошибка при удалении автора: '.$e->getMessage(), 500);
        }
    }
}
