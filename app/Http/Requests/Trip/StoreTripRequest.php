<?php

namespace App\Http\Requests\Trip;

use App\Traits\Validation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTripRequest extends FormRequest
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
            'cost' => 'required|integer',
            'min_cost' => 'required|integer',
            'date_start' => 'required|date',
            'date_end' => 'required|date',
            'tourist_limit' => 'required|integer',
            'bonuses' => 'required|integer',
            'tour_id' => 'nullable|integer',
        ];
    }
}
