<?php

namespace App\Traits;

use App\Helpers\JsonResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait Validation
{
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(JsonResponseHelper::validationError($validator->errors()));
    }
}
