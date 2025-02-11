<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\v1\Brewery;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class IndexBreweryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'by_city' => ['nullable', 'string', 'max:100'],
            'by_name' => ['nullable', 'string', 'max:100'],
            'by_country' => ['nullable', 'string', 'max:100'],
            'by_state' => ['nullable', 'string', 'max:100'],
            'by_postal' => ['nullable', 'string', 'max:100'],
            'by_type' => [
                'nullable',
                'string',
                Rule::in([
                    'micro',
                    'nano',
                    'regional',
                    'brewpub',
                    'large',
                    'planning',
                    'bar',
                    'contract',
                    'proprietor'
                ])
            ],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
            'sort' => [
                'nullable',
                'string',
                Rule::in([
                    'name',
                    'type',
                    'city',
                    'country',
                    'state',
                ])
            ],
            'sort_direction' => [
                'nullable',
                'string',
                Rule::in(['asc', 'desc'])
            ]
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'by_type.in' => 'The selected brewery type is invalid.',
            'per_page.max' => 'The per page value cannot exceed 50.',
            'sort.in' => 'The selected sort field is invalid.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'page' => $this->input('page', 1),
            'per_page' => $this->input('per_page', 10)
        ]);
    }

}
