<?php namespace Piestar\CitationBuilder\Exceptions;

class MissingRequiredFieldsException extends \Exception {
	public function __construct($message, $code = 200, \Exception $previous = null) {
		parent::__construct($message, $code, $previous);
	}
}