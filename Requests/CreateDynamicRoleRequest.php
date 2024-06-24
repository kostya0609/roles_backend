<?php
namespace App\Modules\Roles\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDynamicRoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|integer',
            'title'=>'required',
            'description'=>'required',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Идентификатор пользователя не указан',
            'user_id.integer' => 'Идентификатор пользователя должен быть числом',

            'title.required'=>'Поле "Наименование роли не указано',
            'description.required'=>'Поле "Описание логики работы роли не указано',
        ];
    }
}
