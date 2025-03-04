<?php
namespace App\Modules\Roles\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditPartitionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
			'id' => 'required|integer',
			'parent_id' => 'required_without:title|integer|nullable',
			'title' => 'required_without:parent_id|string',
			'is_active' => 'required|boolean',
		];
    }

    public function messages(): array
    {
        return [
			'id.required' => 'ID раздела не был передан!',
			'id.integer' => 'ID раздела должен быть целым числом!',

			'parent_id.integer' => 'ID родительского раздела должен быть целым числом!',
			'title.string' => 'Название раздела должно быть строкой!',

			'title.required_without' => 'Название раздела обязательно!',
			'parent_id.required_without' => 'Id родительского раздела обязательно!',

			'is_active.required' => 'Поле активности не было передано!',
            'is_active.boolean' => 'Поле активности должно быть булевым значением!',
		];
    }
}