<?php

namespace App\Http\Requests\Review;

use App\Helpers\JsonResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class StoreReviewRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'text' => 'required|string',
            'link' => 'required|string|max:255',
            'img' => 'required|image'
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(JsonResponseHelper::validationError($validator->errors()));
    }
}
