<?php
namespace App\Modules\Roles\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePartitionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'parent_id' => 'nullable|integer',
            'title' => 'required|string',
            'user_id' => 'required|integer',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'parent_id.integer' => 'ID родительского раздела должен быть целым числом!',

            'title.required' => 'Название раздела не было передано!',
            'title.string' => 'Название раздела должно быть строкой!',

            'user_id.required' => 'ID пользователя не было передано!',
            'user_id.integer' => 'ID пользователя должно быть числом!',

            'is_active.required' => 'Поле активности не было передано!',
            'is_active.boolean' => 'Поле активности должно быть булевым значением!',
        ];
    }
}