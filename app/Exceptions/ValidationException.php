<?php
namespace App\Exceptions;

class ValidationException extends \Exception {
	public function __construct ($message, $code = 422) {
		throw new \Exception($message, 422);
	}
}