# QueryRequest (Like FormRequest in Laravel Framework)

Request for validation of url parameters

**How it works**

Suppose you have this url  [www.bzilee.me/portfolio?post_id=1](https://www.bzilee.me),
where you should validate this parameter in the manner of [Laravel's formRequest](https://laravel.com/api/5.5/Illuminate/Foundation/Http/FormRequest.html). The problem is that laravel formRequest only handles data from POST requests.

This package completes the action of formRequests for GET requests.

Just create a validation file exactly like the formRequest inheritors.

And take advantage of laravel's dependency injection to enjoy the same power of formRequest.

## Conditions

The package requires PHP 7.0 or higher. The Laravel package also requires Laravel 5.5 or higher.

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

use Bzilee\Abstracts\QueryRequest;

class RetrievePostRequest extend QueryRequest {
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
	 * Pre Validation Function
     * 
     * @return 
	 */
	protected function prepareForValidation()
	{
        $this->merge([
            // Another query to merge for before validation
        ]);
	}

    /**
	 * Get the error messages for the defined validation rules.
	 *
	 * @return array
	 */
	public function messages()
	{
		return [
			"post_id.required" => "The :attribute is required",
		];
	}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
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
    public function failedValidation()
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

        // your logic code here
    }

}

```


## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## License

The Laravel framework is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).


## Vulnérabilité

Bien vouloir **informer** en cas de doute de sécurité.

## License

MIT - Licence