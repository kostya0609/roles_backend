<?php
namespace App\Modules\Roles\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
{
	public function rules(): array {
		return [
			'count' => 'required|integer',
			'page' => 'required|integer',
			'user_id' => 'required|integer',
		];
	}

	public function messages(): array {
		return [
            'count.required' => 'Необходимое количество записей не указано',
            'count.integer' => 'Необходимое количество записей должено быть числом',

            'page.required' => 'Необходимое страница не указана',
            'page.integer' => 'Необходимое страница должена быть числом',

            'user_id.required' => 'Идентификатор пользователя не указан',
            'user_id.integer' => 'Идентификатор пользователя должен быть числом',
        ];
	}
}
