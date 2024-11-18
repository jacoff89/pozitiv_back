<?php

namespace App\Http\Requests\Tour;

use App\Helpers\JsonResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class StoreTourRequest extends FormRequest
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
            'name' => 'required|string',
            'description' => 'required|string',
            'duration' => 'required|string',
            'place' => 'required|string',
            'plan' => 'required|string',
            'season' => 'required|string',
            'images.*' => 'required|image',
            'images' => 'required|array|max:5',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(JsonResponseHelper::validationError($validator->errors()));
    }
}
