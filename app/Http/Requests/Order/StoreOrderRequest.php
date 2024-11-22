<?php

namespace App\Http\Requests\Order;

use App\Traits\Validation;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'user_id' => 'required|integer',
            'trip_id' => 'required|integer',
            'comment' => 'required|string',
            'additional_services' => 'nullable|array',
            'additional_services.*' => 'required|array',
            'additional_services.*.id' => 'required|integer',
            'additional_services.*.count' => 'required|integer',
            'tourists' => 'required|array',
            'tourists.*' => 'required|integer',
        ];
    }
}
