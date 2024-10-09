<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Override;

/**
 * Класс StoreChapterRequest
 *
 * Отвечает за валидацию данных при создании новой главы.
 *
 * @package App\Http\Requests
 */
final class StoreChapterRequest extends FormRequest
{
    /**
     * @return bool.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Правила валидации.
     *
     * @return array<string, ValidationRule|array|string> Массив правил валидации.
     */
    public function rules(): array
    {
        $bookId = $this->route('bookId');

        return [
            'title' => 'required|string|max:255|unique:chapters,title,NULL,id,book_id,' . $bookId,
            'content' => 'required|string',
        ];
    }

    /**
     * Обрабатывает неудачную попытку валидации.
     *
     * @param Validator $validator Экземпляр валидатора.
     *
     * @throws ValidationException Исключение, выбрасываемое при ошибке валидации.
     * @return void
     */
    #[Override] protected function failedValidation($validator): void
    {
        throw new ValidationException($validator);
    }
}
