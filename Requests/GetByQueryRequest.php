<?php
namespace App\Modules\Roles\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetByQueryRequest extends FormRequest
{
	public function rules(): array {
		return [
			'q' => 'required',
			'user_id' => 'required|integer',
		];
	}

	public function messages(): array {
		return [
            'q.required' => 'Строка поиска не указана',

            'user_id.required' => 'Идентификатор пользователя не указан',
            'user_id.integer' => 'Идентификатор пользователя должен быть числом',
        ];
	}
}
