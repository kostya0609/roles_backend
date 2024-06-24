<?php
namespace App\Modules\Roles\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateStaticRoleRequest extends FormRequest
{
	public function rules(): array
	{
		return [
            'user_id' => 'required|integer',
            'title'=>'required',
            'description'=>'required',
            'users'=>'required | array'
        ];
	}

	public function messages(): array
	{
		return [
            'user_id.required' => 'Идентификатор пользователя не указан',
            'user_id.integer' => 'Идентификатор пользователя должен быть числом',

            'title.required'=>'Поле "Наименование роли не указано',
            'description.required'=>'Поле "Описание логики работы роли не указано',

            'users.required'=>'Поле "Участники не заполнено',
            'users.array' => 'Поле "Участники должно быть массивом c ID участников',
        ];
	}
}
