<?php

namespace App\Http\Requests\Tour;

use App\Traits\Validation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTourRequest extends FormRequest
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
}
