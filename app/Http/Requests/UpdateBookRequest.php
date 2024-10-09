<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Override;

/**
 * Класс запроса для обновления книги.*
 */
final class UpdateBookRequest extends FormRequest
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
     * Правила валидации.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $bookId = $this->route('id');

        return [
            'author_id' => 'required|exists:authors,id',
            'title' => 'required|string|max:255|unique:books,title,' . $bookId . ',id,author_id,' . $this->author_id,
            'annotation' => 'nullable|string|max:1000',
            'publication_date' => 'nullable|date_format:d-m-Y',
        ];
    }

    /**
     * Обрабатывает успешную валидацию запроса и преобразует дату публикации в формат 'Y-m-d'.
     *
     * @return void
     */
    #[Override] public function validateResolved(): void
    {
        parent::validateResolved();

        if ($this->input('publication_date')) {
            $this->merge([
                'publication_date' => Carbon::createFromFormat('d-m-Y', $this->input('publication_date'))->format('Y-m-d'),
            ]);
        }
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
