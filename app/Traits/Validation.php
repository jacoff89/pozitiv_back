<?php

namespace App\Traits;

use App\Helpers\JsonResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

trait Validation
{
    private function transformKeys($data, callable $transformer)
    {
        if (!is_array($data)) {
            return $data;
        }

        $transformed = [];
        foreach ($data as $key => $value) {
            $newKey = $transformer($key);
            $transformed[$newKey] = is_array($value) ? $this->transformKeys($value, $transformer) : $value;
        }

        return $transformed;
    }
    protected function prepareForValidation(): void
    {
        $data = $this->all();
        $snakeCaseData = $this->transformKeys($data, function ($key) {
            return Str::snake($key);
        });

        $this->replace($snakeCaseData);
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(JsonResponseHelper::validationError($validator->errors()));
    }
}
