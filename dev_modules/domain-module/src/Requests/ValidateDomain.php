<?php

namespace Modules\Domain\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateDomain extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'domain' => 'required|regex:/^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}(?![0-9]*$)[a-z0-9-]+\.?)$/igm',
			'parent_domain' => 'nullable|exists:domains,id',
		];
	}

	/**
	 * Get the error messages for the defined validation rules.
	 *
	 * @return array
	 */
	public function messages()
	{
		return [
			'domain.regex' => 'The provided domain is not valid',
			'parent_domain.exists' => 'The parent domain doesn\'t exists',
		];
	}
}
