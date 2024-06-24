<?php
namespace App\Modules\Roles\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckDynamicRoleRequest extends FormRequest
{
	public function rules(): array {
		return [
			'id' =>'required|integer',
            'check_user_id' =>'required|integer',
			'user_id' => 'required|integer',
		];
	}

	public function messages(): array {
		return [
			'id.required' => 'Идентификатор роли не указан',
			'id.integer' => 'Идентификатор роли должен быть числом',

            'check_user_id.required' => 'Идентификатор проверяемого пользователя не указан',
            'check_user_id.integer' => 'Идентификатор проверяемого пользователя должен быть числом',

			'user_id.required' => 'Идентификатор пользователя не указан',
			'user_id.integer' => 'Идентификатор пользователя должен быть числом',
		];
	}
}
