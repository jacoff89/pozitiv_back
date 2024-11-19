<?php

namespace App\Http\Requests\Review;

use App\Traits\Validation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateReviewRequest extends FormRequest
{
    use Validation;

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
            'name' => 'nullable|string|max:255',
            'text' => 'nullable|string',
            'link' => 'nullable|string|max:255',
            'img' => 'nullable|image'
        ];
    }
}
