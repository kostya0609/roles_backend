<?php
namespace App\Modules\Roles\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetByIdRequest extends FormRequest
{
	public function rules(): array {
		return [
			'id' =>'required|integer',
		];
	}

	public function messages(): array {
		return [
			'id.required' => 'Идентификатор роли не указан',
			'id.integer' => 'Идентификатор роли должен быть числом',
		];
	}
}
