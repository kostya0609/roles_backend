<?php
namespace App\Modules\Roles\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetByQueryRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'q' => 'nullable',
		];
	}

	public function messages(): array
	{
		return [];
	}
}
