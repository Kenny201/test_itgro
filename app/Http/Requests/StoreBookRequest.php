<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon;
use Override;

/**
 * Класс StoreBookRequest
 *
 * Отвечает за валидацию данных при создании новой книги.
 *
 * @package App\Http\Requests
 */
final class StoreBookRequest extends FormRequest
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
        return [
            'author_id' => 'required|exists:authors,id',
            'title' => 'required|string|max:255|unique:books,title,NULL,id,author_id,'.$this->author_id,
            'annotation' => 'nullable|string|max:1000',
            'publication_date' => 'required|date_format:d-m-Y',
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
                'publication_date' => Carbon::createFromFormat('d-m-Y',
                    $this->input('publication_date'))->format('Y-m-d'),
            ]);
        }
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
