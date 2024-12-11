<?php

namespace App\Http\Requests\Order;

use App\Traits\Validation;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderWithUserRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string',
            'trip_id' => 'required|integer',
            'comment' => 'nullable|string',
            'additional_services' => 'nullable|array',
            'additional_services.*' => 'required|array',
            'additional_services.*.id' => 'required|integer',
            'additional_services.*.count' => 'required|integer',
            'tourists' => 'nullable|array',
            'tourists.*' => 'required|array',
            'tourists.*.first_name' => 'required|string',
            'tourists.*.last_name' => 'required|string',
            'tourists.*.phone' => 'required|string',
        ];
    }
}
