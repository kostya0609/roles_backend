<?php
namespace App\Modules\Roles\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateStaticRoleRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'user_id' => 'required|integer',
			'title' => 'required|string',
			'description' => 'string|nullable',
			'users' => 'required|array',
			'partition_id' => 'required|integer',
		];
	}

	public function messages(): array
	{
		return [
			'user_id.required' => 'Идентификатор пользователя не указан',
			'user_id.integer' => 'Идентификатор пользователя должен быть числом',

			'title.required' => 'Поле "Наименование роли не указано',
			'title.string' => 'Поле "Наименование роли должно быть строкой',

			'description.string' => 'Поле "Описание роли" должно быть строкой',

			'users.required' => 'Поле "Участники не заполнено',
			'users.array' => 'Поле "Участники должно быть массивом c ID участников',

			'partition_id.required' => 'Идентификатор раздела не указан',
			'partition_id.integer' => 'Идентификатор раздела должен быть числом',
		];
	}
}
