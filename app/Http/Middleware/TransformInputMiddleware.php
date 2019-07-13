<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;

class TransformInputMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $transformer)
    {
        $transformedInputs = [];

        foreach ($request->request->all() as $input => $value) {
            $transformedInput = $transformer::getOriginalAttribute($input);
            $transformedInputs[$transformedInput] = $value;
        }

        $request->replace($transformedInputs);

        $response = $next($request);

        if (isset($response->exception) && $response->exception instanceof ValidationException) {
            $data = $response->getData();

            $transformedErrors = [];
            foreach ($data->errors as $field => $error) {
                $transformedField = $transformer::getTransformedAttribute($field);
                $transformedErrors[$transformedField] = str_replace($field, $transformedField, $error);
            }

            $data->errors = $transformedErrors;

            $response->setData($data);
        }

        return $response;
    }
}
