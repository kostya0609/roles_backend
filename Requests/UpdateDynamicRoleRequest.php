<?php
namespace App\Modules\Roles\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDynamicRoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|integer',
            'id' => 'required|integer',
            'title'=>'required',
            'description'=>'required',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Идентификатор пользователя не указан',
            'user_id.integer' => 'Идентификатор пользователя должен быть числом',

            'id.required' => 'Идентификатор роли не указан',
            'id.integer' => 'Идентификатор роли должен быть числом',

            'title.required'=>'Поле "Наименование роли не указано',
            'description.required'=>'Поле "Описание логики работы роли не указано',

        ];
    }
}
