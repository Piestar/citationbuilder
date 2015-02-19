<?php namespace Piestar\CitationBuilder\Exceptions;

class UnknownWorkTypeException extends \Exception {
	public function __construct($message, $code = 100, \Exception $previous = null) {
		parent::__construct($message, $code, $previous);
	}
}