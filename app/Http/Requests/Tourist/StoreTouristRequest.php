<?php

namespace App\Http\Requests\Tourist;

use App\Traits\Validation;
use Illuminate\Foundation\Http\FormRequest;

class StoreTouristRequest extends FormRequest
{
    use Validation;

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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string',
            'user_id' => 'nullable|integer',
        ];
    }
}
