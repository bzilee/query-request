<?php

namespace Bzilee\Abstracts;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

abstract class QueryRequest
{
	private $rules;
	private $messages = [];
	private $errors;
	private $validated = [];

	abstract protected function authorize();
	abstract protected function rules();
	abstract protected function failedValidation();
	abstract protected function failedAuthorization();

	/**
	 * Set all this configuration
	 *
	 */
	public function __construct()
	{
		$this->setMessages();
		$this->setAuthorize();
		$this->setRules();
	}

	/**
	 * Return the validated query
	 *
	 * @return Array
	 *
	 */
	public function validated()
	{
		return $this->validated;
	}

	/**
	 * Return the error validation
	 *
	 * @return Array
	 *
	 */
	public function errors()
	{
		return $this->errors;
	}

	/**
	 * Register an array of custom validator message replacers.
	 *
	 * @return void
	 */
	private function setMessages()
	{
		if (method_exists(get_class(), "messages")) {
			$this->messages = $this->message();
		}
	}

	/**
	 * Set the authorize function.
	 *
	 * @return void
	 */
	private function setAuthorize()
	{
		if (!$this->authorize()) {
			$this->failedAuthorization();
		}
	}

	/**
	 * Set the validation rules.
	 *
	 * @return void
	 *
	 */
	private function setRules()
	{
		$this->rules = $this->rules();

		if (!is_array($this->rules)) {
			throw new \ErrorException(
				"The rules() function implemented in " . get_class($this) . " must be return an array."
			);
		}

		$validateRequest = Validator::make(request()->query(), $this->rules, $this->messages);

		if ($validateRequest->fails()) {
			$this->errors = $validateRequest->errors();
			$this->failedValidation();
			return;
		} else {
			foreach (request()->query() as $query => $value) {
				if (isset($this->rules[$query])) {
					$this->validated[$query] = $value;
				}
			}
		}
	}
}
