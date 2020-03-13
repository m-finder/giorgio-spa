<?php


namespace GiorgioSpa\Http\Requests\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait RequestErrorMessage
{
    protected function failedValidation(Validator $validator)
    {
        $message = (method_exists($this, 'message'))
            ? $this->container->call([$this, 'message'])
            : 'The given data was invalid.';

        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'message' => $message,
        ], 422));
    }
}
