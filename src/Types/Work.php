<?php namespace Piestar\CitationBuilder\Types;

use Piestar\CitationBuilder\Exceptions\MissingRequiredFieldsException;
use Piestar\CitationBuilder\Exceptions\UnknownWorkTypeException;
use Piestar\CitationBuilder\Styles\CitationStyle;
use Piestar\CitationBuilder\Utility;

class Work {

	public $originalWork;

	protected $requiredFields = ['title'];

	protected $work;

	public static function factory($originalWork) {
		$className = self::checkValid($originalWork);

		/** @var Work $work */
		$work = new $className($originalWork);

		if ($missingFields = $work->checkRequiredFields())  {
			throw new MissingRequiredFieldsException("Publication work is missing required fields: " . implode(',', $missingFields));
		}

		return $work;
	}

	/**
	 * @param array|object $originalWork
	 *
	 * @throws MissingRequiredFieldsException
	 * @throws UnknownWorkTypeException
	 * @throws \Exception
	 */
	public function __construct($originalWork) {
		$className = self::checkValid($originalWork);

		if (static::class !== $className) {
			throw new \Exception("Please use Work::factory for creating Works.");
		}

		$this->originalWork = $originalWork;
		$this->work = (object) $originalWork;

		if ($missingFields = $this->checkRequiredFields())  {
			throw new MissingRequiredFieldsException("Publication work is missing required field(s): " . implode(',', $missingFields));
		}
	}

	/**
	 * @return array
	 */
	public function checkRequiredFields()
	{
		$missingFields = [];
		foreach ($this->requiredFields as $field) {
			if ($this->$field === null) {
				$missingFields[] = $field;
			}
		}

		return $missingFields;
	}

	/**
	 * @param $originalWork
	 *
	 * @return string
	 *
	 * @throws UnknownWorkTypeException
	 */
	protected static function checkValid($originalWork)
	{
		if (is_array($originalWork)) {
			$type = Utility::array_get($originalWork, 'type', null);
		} else {
			$type = $originalWork->type;
		}

		$className = __NAMESPACE__ . '\\' . ucfirst(strtolower($type));
		if ( ! class_exists($className)) {
			throw new UnknownWorkTypeException("Unknown type $type.");
		}

		return $className;
	}

	/**
	 * @param CitationStyle $style
	 *
	 * @throws UnknownWorkTypeException
	 *
	 * @return string
	 */
	public function citeHtml(CitationStyle $style) {
		$method = $this->type;

		if ( ! method_exists($style, $method)) {
			throw new UnknownWorkTypeException("Style $style does not support $this->type works.");
		}

		return $style->$method($this);
	}

	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function __get($name) {
		return isset($this->work->$name) ? $this->work->$name : null;
	}
}