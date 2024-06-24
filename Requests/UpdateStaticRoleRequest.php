<?php
namespace App\Modules\Roles\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStaticRoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|integer',
            'id' => 'required|integer',
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

            'id.required' => 'Идентификатор роли не указан',
            'id.integer' => 'Идентификатор роли должен быть числом',

            'title.required'=>'Поле "Наименование роли не указано',
            'description.required'=>'Поле "Описание логики работы роли не указано',

            'users.required'=>'Поле "Участники не заполнено',
            'users.array' => 'Поле "Участники должно быть массивом c ID участников',
        ];
    }
}
