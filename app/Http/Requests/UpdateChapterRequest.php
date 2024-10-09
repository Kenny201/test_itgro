<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Override;

/**
 * Класс запроса для обновления главы.
 *
 * Этот класс обрабатывает валидацию данных при обновлении главы книги,
 * таких как заголовок и содержание главы.
 */
final class UpdateChapterRequest extends FormRequest
{
    /**
     * Определяет, авторизован ли пользователь для выполнения этого запроса.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Правила валидации, которые применяются к запросу.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ];
    }

    /**
     * Обработка ошибок валидации.
     *
     * @param Validator $validator
     *
     * @throws ValidationException
     */
    #[Override] protected function failedValidation($validator)
    {
        throw new ValidationException($validator);
    }
}
