# QueryRequest Abstract Class

Request for validation of url parameters

**Generate PDF tables with Javascript**

Suppose you have this url  [www.bzilee.me/portfolio?post_id=1](https://www.bzilee.me),
where you should validate this parameter in the manner of [Laravel's formRequest](https://laravel.com/api/5.5/Illuminate/Foundation/Http/FormRequest.html). The problem is that laravel formRequest only handles data from POST requests.

This package completes the action of formRequests for GET requests.

Just create a validation file exactly like the formRequest inheritors.

And take advantage of laravel's dependency injection to enjoy the same power of formRequest.

## Installation

Get the package by doing these things:

```shel

$ composer require bzilee/query-request

$ composer dump-autoload

```

## Usage

```php

<?php

/**
 * QueryRequest Class
 */

namespace App\Http\Requests;

use Bzilee\QueryRequest;

class RetrievePostRequest extend QueryRequest {
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            "post_id" => "required|numeric|exists:posts,id,deleted_at,NULL",
        ];
    }

    /**
     * If validator fails return the exception in json form
     * 
     * @return ResponseException
     */
    protected function failedValidation()
    {
        // your logic code here
        // example
        throw new \Exception($this->errors());
    }

    /**
     *
     */
    protected function failedAuthorization()
    {
        // your logic code here
        throw new \Exception("UnAuthorization");
    }
}

```

```php

<?php

/**
 * QueryRequest Class
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RetrievePostRequest;

class PostController extend Controller {
    
    /**
     * Display a listing of the resource.
     *
     * @param RetrievePostRequest $request
     */
    protected function index(RetrievePostRequest $request)
    {
        $data = $request->validated();
    }

}

```
## Usage
