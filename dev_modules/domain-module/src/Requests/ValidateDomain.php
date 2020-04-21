<?php

namespace Modules\Domain\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Domain\Models\Domain;

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
            'domain' => 'required',
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

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->sometimes('domain', 'regex:/^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}(?![0-9]*$)[a-z0-9-]+\.?)$/im', function ($input) {
            return $input->parent_domain === null;
        });

        $validator->after(function ($validator) {
            if ($this->get('parent_domain') === null) {
                $exploded = explode('.', $this->get('domain'));

                if (Domain::where('name', $exploded[0])->where('extension', $exploded[1])->exists()) {
                    $validator->errors()->add('domain', 'The provided domain already exists');
                }
            } else {
                if (Domain::where('name', $this->get('domain'))->where('parent_domain', $this->get('parent_domain'))->exists()) {
                    $validator->errors()->add('domain', 'The provided subdomain already exists');
                }
            }
        });
    }
}
