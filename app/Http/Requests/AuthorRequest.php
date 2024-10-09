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
 * Класс AuthorRequest
 *
 * Отвечает за валидацию данных, связанных с автором, при создании или обновлении автора.
 *
 * @package App\Http\Requests
 */
final class AuthorRequest extends FormRequest
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
            'name' => 'required|string|min:2|max:40|unique:authors,name', // Имя автора (обязательно, строка, уникальное)
            'info' => 'nullable|string|max:1000', // Информация об авторе (необязательно, строка)
            'birthdate' => 'nullable|date', // Дата рождения (необязательно, формат дд-мм-гггг)
        ];
    }

    /**
     * Обрабатывает дополнительную подготовку данных перед валидацией.
     * Преобразует 'birthdate' из формата дд-мм-гггг в формат гггг-мм-дд для хранения в базе данных.
     *
     * @return void
     */
    #[Override] public function validateResolved(): void
    {
        parent::validateResolved();

        if ($this->input('birthdate')) {
            $this->merge([
                'birthdate' => Carbon::createFromFormat('d-m-Y', $this->input('birthdate'))->format('Y-m-d'),
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
