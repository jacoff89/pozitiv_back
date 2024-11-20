<?php

namespace App\Http\Requests\Trip;

use App\Traits\Validation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateTripRequest extends FormRequest
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
            'cost' => 'nullable|integer',
            'min_cost' => 'nullable|integer',
            'date_start' => 'nullable|date',
            'date_end' => 'nullable|date',
            'tourist_limit' => 'nullable|integer',
            'bonuses' => 'nullable|integer',
            'tour_id' => 'nullable|integer',
        ];
    }
}
