<?php namespace Piestar\CitationBuilder;

use Piestar\CitationBuilder\Styles\CitationStyle;
use Piestar\CitationBuilder\Types\Work;

class CitationBuilder {

	const TYPE_BOOK = 'book';
	const TYPE_CHAPTER = 'chapter';
	const TYPE_ESSAY = 'chapter';
	const TYPE_JOURNAL = 'journal';
	const TYPE_NEWSPAPER = 'newspaper';
	const TYPE_MAGAZINE = 'magazine';
	const TYPE_WEBSITE = 'website';

	const MEDIUM_PRINT    = 'print';
	const MEDIUM_WEB      = 'website';
	const MEDIUM_DATABASE = 'db';
	const MEDIUM_EBOOK    = 'ebook';

	/**
	 * @var Work[]
	 */
	private $works = [];

	/**
	 * @param array|\Traversable $userWorks
	 */
	public function __construct($userWorks) {

		foreach ($userWorks as $userWork) {
			$this->works[] = Work::factory($userWork);
		}
	}

	/**
	 * @param CitationStyle $style
	 *
	 * @return array
	 */
	public function citeHtml(CitationStyle $style) {
		$ret = [];
		foreach ($this->works as $work) {
			$ret[] = $work->citeHtml($style);
		}
		return $ret;
	}
}