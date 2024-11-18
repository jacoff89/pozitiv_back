<?php

namespace App\Http\Requests\Tour;

use App\Helpers\JsonResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class UpdateTourRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user() && Auth::user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string',
            'description' => 'nullable|string',
            'duration' => 'nullable|string',
            'place' => 'nullable|string',
            'plan' => 'nullable|string',
            'season' => 'nullable|string',
            'images.*' => 'nullable|image',
            'images' => 'nullable|array|max:5',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(JsonResponseHelper::validationError($validator->errors()));
    }
}
