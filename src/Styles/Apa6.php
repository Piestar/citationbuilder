<?php namespace Piestar\CitationBuilder\Styles;

use Piestar\CitationBuilder\Types\Work;
use Piestar\CitationBuilder\Utility;

/**
 * American Psychological Association (APA) format
 */
class Apa6 implements CitationStyle {

	/** Format a date published (APA)
	 *
	 * @param $day
	 * @param $month
	 * @param $year
	 *
	 * @return string
	 */
	function formatPublishDate($day, $month, $year)
	{
		if ( ! $day && ! $month && ! $year) {
			$apamagnewsdate = '(n.d.)';
		} else {
			$apamagnewsdate = '(' . $year . ', ' . $month;
			if ($day) {
				$apamagnewsdate .= ' ' . $day;
			}
			$apamagnewsdate .= ')';
		}

		return $apamagnewsdate;
	}

	/**
	 * Format page numbers for a newspaper citing (APA)
	 *
	 * @param $startPage
	 * @param $endPage
	 * @param $hasNonConsecutivePages
	 * @param $nonConsecutivePageNums
	 *
	 * @return string
	 */
	function formatNewspaperPageNumbers($startPage, $endPage, $hasNonConsecutivePages, $nonConsecutivePageNums)
	{
		if (($startPage == $endPage || $startPage && ! $endPage) && ($startPage && ! $hasNonConsecutivePages)) {
				//if start page is less than end page and the pages are consecutive
			$ret = 'p. ' . ucwords($startPage);

			return $ret;
		}
		if ($startPage < $endPage && ! $hasNonConsecutivePages) {
				//if start page is less than end page and the pages are consecutive
			$ret = 'pp. ' . ucwords($startPage) . "-" . ucwords($endPage);

			return $ret;
		}
		if ($hasNonConsecutivePages && $nonConsecutivePageNums) {
				//if the pages are not consecutive and there are page numbers to display
			$ret = 'pp. ' . $nonConsecutivePageNums;

			return $ret;
		}
	}

	/**
	 * Format page numbers for a scholarly journal citing (APA)
	 *
	 * @param $startPage
	 * @param $endPage
	 * @param $hasNonConsecutivePages
	 * @param $nonConsecutivePageNums
	 *
	 * @return string
	 */
	function formatJournalPageNumbers($startPage, $endPage, $hasNonConsecutivePages, $nonConsecutivePageNums)
	{
		if (($startPage == $endPage || $startPage && ! $endPage) && ($startPage && ! $hasNonConsecutivePages)) {
				//if start page equals end page or there is a start page, but no end page
			$ret = ucwords($startPage);

			return $ret;
		}
		if ($startPage < $endPage && ! $hasNonConsecutivePages) {
				//if start page is less than end page and the pages are consecutive
			$ret = ucwords($startPage) . "-" . ucwords($endPage);

			return $ret;
		}
		if ($hasNonConsecutivePages && $nonConsecutivePageNums) {
				//if the pages are not consecutive and there are page numbers to display
			$ret = $nonConsecutivePageNums;

			return $ret;
		}
	}

	/**
	 * Format the author names (APA)
	 *
	 * @param $authors
	 *
	 * @return string
	 */
	function formatAuthors($authors)
	{
		$authors = (array)$authors;
			//Count the number of contributors in the array
		$count = count($authors);
			//Count the number of authors in the array
		$numAuthors = 0;
		foreach ($authors as $contributor) {
			if (Utility::array_get($contributor, 'cselect') == 'author') {
				$numAuthors ++;
			}
		}
		$ret = '';
		for ($i = 0; $i < $count; $i ++) {
				//If this contributor is an author
			if (Utility::array_get(Utility::array_get($authors, $i, []), 'cselect') == 'author') {
				if ($i == 0) {
						//First time through the loop
					if ($numAuthors > 1) {
							//There is more than one author
						$ret .= ucwords($authors[ $i ]['lname']);
						if (($authors[ $i ]['fname'] || $authors[ $i ]['mi'])) {
								//The author is a person and not a corporation
								//Check for a hyphen in the first name
							$hyphentest = stripos($authors[ $i ]['fname'], '-');
							if ($hyphentest != false) {
								$ret .= ', ' . Utility::firstInitial($authors[ $i ]['fname']) . '.-';
							} else {
								$ret .= ', ' . Utility::firstInitial($authors[ $i ]['fname']) . '.';
							}
							if ($authors[ $i ]['mi']) {
								$ret .= ' ' . ucwords($authors[ $i ]['mi']) . '., ';
							} else {
								$ret .= ', ';
							}
						} else {
								//The author is a corporation and not a person
							$ret .= ', ';
						}
					} else {
							//There is only one author
						if (($authors[ $i ]['lname'] != 'Anonymous') || ( ! $authors[ $i ]['lname'] && ! $authors[ $i ]['fname'] && ! $authors[ $i ]['mi'])) {
								//The author is not Anonymous or blank
							$ret .= ucwords($authors[ $i ]['lname']);
							if (($authors[ $i ]['fname'] || $authors[ $i ]['mi'])) {
									//The author is a person and not a corporation
									//Check for a hyphen in the first name
								$hyphentest = stripos($authors[ $i ]['fname'], '-');
								if ($hyphentest != false) {
									$ret .= ', ' . Utility::firstInitial($authors[ $i ]['fname']) . '.-';
								} else {
									$ret .= ', ' . Utility::firstInitial($authors[ $i ]['fname']) . '. ';
								}
								if ($authors[ $i ]['mi']) {
									$ret .= ucwords($authors[ $i ]['mi']) . '. ';
								}
							} else {
									//The author is a corporation and not a person
								$ret .= '. ';
							}
						}
					}
				} elseif ($i >= 5) {
						//Sixth or more time through the loop
					if ($numAuthors > 7 && $i == 5) {
							//There are more than 7 authors and this is the sixth time through the loop
						$ret .= ' ' . ucwords($authors[ $i ]['lname']) . ', ';
						if (($authors[ $i ]['fname'] || $authors[ $i ]['mi'])) {
								//The author is a person and not a corporation
								//Check for a hyphen in the first name
							$hyphentest = stripos($authors[ $i ]['fname'], '-');
							if ($hyphentest != false) {
								$ret .= Utility::firstInitial($authors[ $i ]['fname']) . '.-';
							} else {
								$ret .= Utility::firstInitial($authors[ $i ]['fname']) . '.';
							}
							if ($authors[ $i ]['mi']) {
								$ret .= ' ' . ucwords($authors[ $i ]['mi']) . '.';
							}
							$ret .= ', . . . ';
						} else {
								//The author is a corporation and not a person
							$ret .= ', . . . ';
						}
					} elseif ($numAuthors == 7 && $i == 5) {
							//There are 7 authors and this is the sixth time through the loop
						$ret .= ' ' . ucwords($authors[ $i ]['lname']);
						if (($authors[ $i ]['fname'] || $authors[ $i ]['mi'])) {
								//The author is a person and not a corporation
								//Check for a hyphen in the first name
							$hyphentest = stripos($authors[ $i ]['fname'], '-');
							if ($hyphentest != false) {
								$ret .= ', ' . Utility::firstInitial($authors[ $i ]['fname']) . '.-';
							} else {
								$ret .= ', ' . Utility::firstInitial($authors[ $i ]['fname']) . '. ';
							}
							if ($authors[ $i ]['mi']) {
								$ret .= ucwords($authors[ $i ]['mi']) . '., & ';
							} else {
								$ret .= ucwords($authors[ $i ]['mi']) . ', & ';
							}
						} else {
								//The author is a corporation and not a person
							$ret .= ', & ';
						}
					} elseif (($i + 1) == $count) {
							//This is the last time through the loop
							//If there are 6 authors add an ampersand before the name, otherwise do not
						if ($numAuthors == 6) {
							$ret .= ' & ' . ucwords($authors[ $i ]['lname']);
							if (($authors[ $i ]['fname'] || $authors[ $i ]['mi'])) {
									//The author is a person and not a corporation
									//Check for a hyphen in the first name
								$hyphentest = stripos($authors[ $i ]['fname'], '-');
								if ($hyphentest != false) {
									$ret .= ', ' . Utility::firstInitial($authors[ $i ]['fname']) . '.-';
								} else {
									$ret .= ', ' . Utility::firstInitial($authors[ $i ]['fname']) . '. ';
								}
								if ($authors[ $i ]['mi']) {
									$ret .= ucwords($authors[ $i ]['mi']) . '. ';
								}
							} else {
									//The author is a corporation and not a person
								$ret .= '. ';
							}
						} else {
							$ret .= ' ' . ucwords($authors[ $i ]['lname']);
							if (($authors[ $i ]['fname'] || $authors[ $i ]['mi'])) {
									//The author is a person and not a corporation
									//Check for a hyphen in the first name
								$hyphentest = stripos($authors[ $i ]['fname'], '-');
								if ($hyphentest != false) {
									$ret .= ', ' . Utility::firstInitial($authors[ $i ]['fname']) . '.-';
								} else {
									$ret .= ', ' . Utility::firstInitial($authors[ $i ]['fname']) . '. ';
								}
								if ($authors[ $i ]['mi']) {
									$ret .= ucwords($authors[ $i ]['mi']) . '. ';
								}
							} else {
									//The author is a corporation and not a person
								$ret .= '. ';
							}
						}
					}
				} else {
					if (($i + 1) == $count) {
							//This is the last time through the loop
						if ($numAuthors > 1) {
								//There is more than one author
							$ret .= ' & ' . ucwords($authors[ $i ]['lname']);
							if (($authors[ $i ]['fname'] || $authors[ $i ]['mi'])) {
									//The author is a person and not a corporation
									//Check for a hyphen in the first name
								$hyphentest = stripos($authors[ $i ]['fname'], '-');
								if ($hyphentest != false) {
									$ret .= ', ' . Utility::firstInitial($authors[ $i ]['fname']) . '.-';
								} else {
									$ret .= ', ' . Utility::firstInitial($authors[ $i ]['fname']) . '.';
								}
								if ($authors[ $i ]['mi']) {
									$ret .= ' ' . ucwords($authors[ $i ]['mi']) . '. ';
								}
								$ret .= ' ';
							} else {
									//The author is a corporation and not a person
								$ret .= '. ';
							}
						} else {
								//There is only one author
							if (($authors[ $i ]['lname'] != 'Anonymous') || ( ! $authors[ $i ]['lname'] && ! $authors[ $i ]['fname'] && ! $authors[ $i ]['mi'])) {
									//The author is not Anonymous or blank
								$ret .= ucwords($authors[ $i ]['lname']);
								if (($authors[ $i ]['fname'] || $authors[ $i ]['mi'])) {
										//The author is a person and not a corporation
										//Check for a hyphen in the first name
									$hyphentest = stripos($authors[ $i ]['fname'], '-');
									if ($hyphentest != false) {
										$ret .= ', ' . Utility::firstInitial($authors[ $i ]['fname']) . '.-';
									} else {
										$ret .= ', ' . Utility::firstInitial($authors[ $i ]['fname']) . '. ';
									}
									if ($authors[ $i ]['mi']) {
										$ret .= ucwords($authors[ $i ]['mi']) . '. ';
									}
								} else {
										//The author is a corporation and not a person
									$ret .= '. ';
								}
							}
						}
					} else {
						$ret .= ' ' . ucwords($authors[ $i ]['lname']);
						if (($authors[ $i ]['fname'] || $authors[ $i ]['mi'])) {
								//The author is a person and not a corporation
								//Check for a hyphen in the first name
							$hyphentest = stripos($authors[ $i ]['fname'], '-');
							if ($hyphentest != false) {
								$ret .= ', ' . Utility::firstInitial($authors[ $i ]['fname']) . '.-';
							} else {
								$ret .= ', ' . Utility::firstInitial($authors[ $i ]['fname']) . '.';
							}
							if ($authors[ $i ]['mi']) {
								$ret .= ' ' . ucwords($authors[ $i ]['mi']) . '.,';
							} else {
								$ret .= ', ';
							}
						} else {
								//The author is a corporation and not a person
							$ret .= ', ';
						}
					}
				}
			}
		}

		return $ret;
	}

	/**
	 * Format the translator names (APA)
	 *
	 * @param array $translators
	 *
	 * @return string
	 */
	function formatTranslators($translators)
	{
		return '';

		$count = count($translators);
			//Count the number of authors in the array
		$numAuthors = 0;
			//Count the number of translators in the array
		$numTranslators = 0;
		foreach ($translators as $contributor) {
			if (Utility::array_get($contributor, 'cselect') == 'author') {
				$numAuthors ++;
			} elseif (Utility::array_get($contributor, 'cselect') == 'translator') {
				$numTranslators ++;
			}
		}
		$ret = '';
			//Translator iterative counter
		$t = 0;
		for ($i = 0; $i < $count; $i ++) {
			if ($translators[ $i ]['cselect'] == 'translator') {
					//If this contributor is an translator
				if ($t == 0) {
						//First time through the loop
					if ($numTranslators > 1) {
							//There is more than one translator
						$ret .= '(';
						$ret .= substr(ucwords($translators[ $i ]['fname']), 0, 1) . '. ';
						if ($translators[ $i ]['mi']) {
							$ret .= ucwords($translators[ $i ]['mi']) . '. ';
						}
						$ret .= ucwords($translators[ $i ]['lname']);
						if ($numTranslators > 2) {
								//There are more than two translators
							$ret .= ',';
						}
					} else {
							//There is only one translator
						if (($translators[ $i ]['lname'] != 'Anonymous') || ( ! $translators[ $i ]['lname'] && ! $translators[ $i ]['fname'] && ! $translators[ $i ]['mi'])) {
								//The translator is not Anonymous or blank
							$ret .= '(';
							$ret .= substr(ucwords($translators[ $i ]['fname']), 0, 1) . '. ';
							if ($translators[ $i ]['mi']) {
								$ret .= ucwords($translators[ $i ]['mi']) . '. ';
							}
							$ret .= ucwords($translators[ $i ]['lname']);
						}
					}
				} elseif (($t + 1) == $numTranslators) {
						//Last time through the loop
					if ($numTranslators > 1) {
							//There is more than one translator
						$ret .= ' & ' . substr(ucwords($translators[ $i ]['fname']), 0, 1) . '. ';
						if ($translators[ $i ]['mi']) {
							$ret .= ucwords($translators[ $i ]['mi']) . '. ';
						}
						$ret .= ucwords($translators[ $i ]['lname']);
					} else {
							//There is only one translator
						if (($translators[ $i ]['lname'] != 'Anonymous') || ( ! $translators[ $i ]['lname'] && ! $translators[ $i ]['fname'] && ! $translators[ $i ]['mi'])) {
								//The translator is not Anonymous or blank
							$ret .= '(';
							$ret .= substr(ucwords($translators[ $i ]['fname']), 0, 1) . '. ';
							if ($translators[ $i ]['mi']) {
								$ret .= ucwords($translators[ $i ]['mi']) . '. ';
							}
							$ret .= ucwords($translators[ $i ]['lname']);
						}
					}
				} elseif (($t + 2) == $numTranslators) {
						//Second to last time through the loop
					$ret .= ' ' . substr(ucwords($translators[ $i ]['fname']), 0, 1) . '. ';
					if ($translators[ $i ]['mi']) {
						$ret .= ucwords($translators[ $i ]['mi']) . '. ';
					}
					$ret .= ucwords($translators[ $i ]['lname']);
				} else {
					$ret .= ' ' . substr(ucwords($translators[ $i ]['fname']), 0, 1) . '. ';
					if ($translators[ $i ]['mi']) {
						$ret .= ucwords($translators[ $i ]['mi']) . '. ';
					}
					$ret .= ucwords($translators[ $i ]['lname']) . ',';
				}
				$t ++;
			}
		}
		if ($numTranslators > 0) {
			$ret .= ', Trans.).';
		}

		return $ret;
	}

	/**
	 * Format the editor names (APA)
	 *
	 * @param array $editors
	 *
	 * @return string
	 */
	function formatEditors($editors)
	{
		return '';

		$count = count($editors);
			//Count the number of authors in the array
		$numAuthors = 0;
			//Count the number of editors in the array
		$numEditors = 0;
		foreach ($editors as $editor) {
			if ($editor['cselect'] == 'author') {
				$numAuthors ++;
			} elseif ($editor['cselect'] == 'editor') {
				$numEditors ++;
			}
		}
		$ret = '';
			//editor iterative counter
		$t = 0;
		for ($i = 0; $i < $count; $i ++) {
			if ($editors[ $i ]['cselect'] == 'editor') {
					//If this contributor is an editor
				if ($t == 0) {
						//First time through the loop
					if ($numEditors > 1) {
							//There is more than one editor
						$ret .= 'In ';
						if ($editors[ $i ]['fname']) {
							$ret .= substr(ucwords($editors[ $i ]['fname']), 0, 1) . '. ';
						}
						if ($editors[ $i ]['mi']) {
							$ret .= ' ' . ucwords($editors[ $i ]['mi']) . '. ';
						}
						$ret .= ucwords($editors[ $i ]['lname']);
						if ($numEditors > 2) {
								//There are more than two editors
							$ret .= ',';
						}
					} else {
							//There is only one editor
						$ret .= 'In ';
						if (($editors[ $i ]['lname'] != 'Anonymous') || ( ! $editors[ $i ]['lname'] && ! $editors[ $i ]['fname'] && ! $editors[ $i ]['mi'])) {
								//The editor is not Anonymous or blank
							if ($editors[ $i ]['fname']) {
								$ret .= substr(ucwords($editors[ $i ]['fname']), 0, 1) . '. ';
							}
							if ($editors[ $i ]['mi']) {
								$ret .= ' ' . ucwords($editors[ $i ]['mi']) . '. ';
							}
							$ret .= ucwords($editors[ $i ]['lname']);
						}
					}
				} elseif (($t + 1) == $numEditors) {
						//Last time through the loop
					if ($numEditors > 1) {
							//There is more than one editor
						if ($editors[ $i ]['fname']) {
							$ret .= ' & ' . substr(ucwords($editors[ $i ]['fname']), 0, 1) . '. ';
						}
						if ($editors[ $i ]['mi']) {
							$ret .= ' ' . ucwords($editors[ $i ]['mi']) . '. ';
						}
						$ret .= ucwords($editors[ $i ]['lname']);
					} else {
							//There is only one editor
						if (($editors[ $i ]['lname'] != 'Anonymous') || ( ! $editors[ $i ]['lname'] && ! $editors[ $i ]['fname'] && ! $editors[ $i ]['mi'])) {
								//The editor is not Anonymous or blank
							if ($editors[ $i ]['fname']) {
								$ret .= substr(ucwords($editors[ $i ]['fname']), 0, 1) . '. ';
							}
							if ($editors[ $i ]['mi']) {
								$ret .= ' ' . ucwords($editors[ $i ]['mi']) . '. ';
							}
							$ret .= ucwords($editors[ $i ]['lname']);
						}
					}
				} elseif (($t + 2) == $numEditors) {
						//Second to last time through the loop
					if ($editors[ $i ]['fname']) {
						$ret .= ' ' . substr(ucwords($editors[ $i ]['fname']), 0, 1) . '. ';
					}
					if ($editors[ $i ]['mi']) {
						$ret .= ' ' . ucwords($editors[ $i ]['mi']) . '. ';
					}
					$ret .= ucwords($editors[ $i ]['lname']);
				} else {
					if ($editors[ $i ]['fname']) {
						$ret .= ' ' . substr(ucwords($editors[ $i ]['fname']), 0, 1) . '. ';
					}
					if ($editors[ $i ]['mi']) {
						$ret .= ' ' . ucwords($editors[ $i ]['mi']) . '. ';
					}
					$ret .= ucwords($editors[ $i ]['lname']) . ',';
				}
				$t ++;
			}
		}
		if ($numEditors == 1) {
			$ret .= ' (Ed.),';
		}
		if ($numEditors > 1) {
			$ret .= ' (Eds.),';
		}

		return $ret;
	}

	/**
	 * Format an article title (APA)
	 *
	 * @param $articleTitle
	 *
	 * @return string
	 */
	function formatTitle($articleTitle)
	{
		//Uppercase the first word in article title
		$articleTitle = ucfirst(strtolower($articleTitle));
		//If the article title contains a subtitle, capitalize the first word after the colon
		if (preg_match('/:[ ]+[a-z]/', $articleTitle, $matches)) {
			$articleTitle = Utility::uppercaseSubtitle($articleTitle);
		}
		//Punctuate after the article title
		$articleTitle = Utility::addPeriod($articleTitle);

		return $articleTitle;
	}

	/**
	 * Format a book title (APA)
	 *
	 * @param $title
	 *
	 * @return string
	 */
	function formatBookTitle($title)
	{
		//Uppercase the first word in article title
		$ret = ucfirst(strtolower($title));
		//If the article title contains a subtitle, capitalize the first word after the colon
		$ret = Utility::uppercaseSubtitle($ret);
		//Punctuate after the book title, if necessary
		$ret = Utility::addPeriod($ret);
		$ret = '<i>' . $ret . '</i>';

		return $ret;
	}

	/********************************/
	/*     Citation parsing         */
	/********************************/

	/**
	 * Creates a book (in entirety) citation
	 *
	 * @param Work $work
	 *
	 * @return string
	 */
	function book(Work $work)
	{
			//Add the contributors
		$ret = $this->formatAuthors($work->authors);
			//Add the publishing date (if provided)
		if ($work->publicationYear) {
			$ret .= ' (' . $work->publicationYear . '). ';
		}
			//Add the book title (if provided)
		if ($work->title) {
			$ret .= $this->formatBookTitle($work->title) . ' ';
		}
			//Add the translators (if provided)
		$ret .= $this->formatTranslators($work->authors) . ' ';
			//Add the editors (if provided)
		$ret .= $this->formatEditors($work->authors) . ' ';
			//in print
		if ($work->medium == "print") {
				//Add the publisher location (if provided)
			if ($work->publisherLocation) {
				$ret .= ucwords($work->publisherLocation) . ': ';
			}
				//Add the publisher (if provided)
			if ($work->publisher) {
				$ret .= ucwords($work->publisher) . '.';
			}
		}
			//on a website
		if ($work->medium == "website") {
				//Add the URL (if provided)
			if ($work->webUrl) {
				$ret .= 'Retrieved from ' . Utility::checkUrlPrepend($work->webUrl);
			} elseif ($work->webDoi) {
					//Add the DOI (if provided)
				$ret .= 'doi:' . $work->webDoi;
			}
		}
			//in a database
		if ($work->medium == "db") {
				//Add the URL (if provided)
			if ($work->dbUrl) {
				$ret .= 'Retrieved from ' . Utility::checkUrlPrepend($work->dbUrl);
			} elseif ($work->dbDoi) {
					//Add the DOI (if provided)
				$ret .= 'doi:' . $work->dbDoi;
			}
		}
			//as an ebook
		if ($work->medium == "ebook") {
				//Add the URL (if provided)
			if ($work->ebookUrl) {
				$ret .= 'Retrieved from ' . Utility::checkUrlPrepend($work->ebookUrl);
			} elseif ($work->ebookDoi) {
					//Add the DOI (if provided)
				$ret .= 'doi:' . $work->ebookDoi;
			}
		}
		return $ret;
	}

	/**
	 * Creates a chapter or essay from a book citation
	 *
	 * @param Work $work
	 *
	 * @return string
	 */
	function chapter(Work $work)
	{
			//Add the contributors
		$ret = $this->formatAuthors($work->authors);
			//Add the publishing date (if provided)
		if ($work->publicationYear) {
			$ret .= ' (' . $work->publicationYear . '). ';
		}
			//Add the chapter/essay title (if provided)
		if ($work->title) {
			$ret .= $this->formatTitle($work->title) . ' ';
		}
			//Add the translators (if provided)
		$ret .= $this->formatTranslators($work->authors) . ' ';
			//Add the editors (if provided)
		if ($this->formatEditors($work->authors)) {
			$ret .= $this->formatEditors($work->authors) . ' ';
		} else {
			$ret .= 'In ';
		}
			//Add the book title and page numbers (if provided)
		$pageholder = $this->formatNewspaperPageNumbers($work->startPage, $work->endPage, $work->hasNonConsecutivePages, $work->nonConsecutivePageNums);
		if ($pageholder) {
				//There are page numbers to display
			if ($work->bookTitle) {
					//There is a book title to display
				$ret .= $this->formatBookTitle($work->bookTitle, "no") . ' ';
			}
			$ret .= '(' . $pageholder . '). ';
		} else {
				//There are no page numbers to display
			if ($work->bookTitle) {
					//There is a book title to display
				$ret .= $this->formatBookTitle($work->bookTitle, "yes") . ' ';
			}
		}
			//Add the publisher location (if provided)
		if ($work->publisherLocation) {
			$ret .= ucwords($work->publisherLocation) . ': ';
		}
			//Add the publisher (if provided)
		if ($work->publisher) {
			$ret .= ucwords($work->publisher) . '. ';
		}
			//on a website
		if ($work->medium == "website") {
				//Add the URL (if provided)
			if ($work->webUrl) {
				$ret .= 'Retrieved from ' . Utility::checkUrlPrepend($work->webUrl);
			} elseif ($work->webDoi) {
					//Add the DOI (if provided)
				$ret .= 'doi:' . $work->webDoi;
			}
		}
			//in a database
		if ($work->medium == "db") {
				//Add the URL (if provided)
			if ($work->dbUrl) {
				$ret .= 'Retrieved from ' . Utility::checkUrlPrepend($work->dbUrl);
			} elseif ($work->dbDoi) {
					//Add the DOI (if provided)
				$ret .= 'doi:' . $work->dbDoi;
			}
		}
		return $ret;
	}

	/**
	 * Creates a magazine article citation
	 *
	 * @param Work $work
	 *
	 * @return string
	 */
	function magazine(Work $work)
	{
			//Add the contributors
		$ret = $this->formatAuthors($work->authors);
			//Add the publishing date
		$ret .= $this->formatPublishDate($work->day, $work->month, $work->year) . '. ';
			//Add the article title (if provided)
		if ($work->articleTitle) {
			$ret .= $this->formatTitle($work->articleTitle) . ' ';
		}
			//Add the magazine title (if provided)
		if ($work->magazineTitle) {
			$magtitleholder = ucwords($work->magazineTitle);
			$ret .= '<i>' . Utility::lowerArticles($magtitleholder) . '</i>';
		}
		if ($work->medium == "print") {
				//Add the volume and issue numbers (if provided)
			if ($work->printAdvancedInfoVolume || $work->printAdvancedInfoIssue) {
					//Add a comma after the magazine title (if provided)
				if ($work->magazineTitle) {
					$ret .= ', ';
				}
				$ret .= '<i>' . $work->printAdvancedInfoVolume . '</i>';
				if ($work->printAdvancedInfoIssue) {
						//Add the issue number (if provided)
					$ret .= '(' . $work->printAdvancedInfoIssue . ')';
				}
			}
				//Add the page numbers (if provided)
			$pageholder = $this->formatJournalPageNumbers($work->startPage, $work->endPage, $work->hasNonConsecutivePages, $work->nonConsecutivePageNums);
			if ($pageholder) {
					//There are page numbers
				if ($work->printAdvancedInfoVolume || $work->printAdvancedInfoIssue) {
						//There is a volume & issue number preceeding
					$ret .= ', ' . $pageholder;
				} else {
						//There is no volume & issue number preceeding
					if ($work->magazineTitle) {
							//There is a magazine title preceeding
						$ret .= ', ' . $pageholder;
					} else {
							//There is no magazine title preceeding
						$ret .= $pageholder;
					}
				}
			}
				//Add a period
			$ret .= '. ';
		}
		if ($work->medium == "website") {
				//Add the volume and issue numbers (if provided)
			if ($work->webAdvancedInfoVolume || $work->webAdvancedInfoIssue) {
					//Add a comma after the magazine title (if provided)
				if ($work->magazineTitle) {
					$ret .= ', ';
				}
				$ret .= '<i>' . $work->webAdvancedInfoVolume . '</i>';
				if ($work->webAdvancedInfoIssue) {
						//Add the issue number (if provided)
					$ret .= '(' . $work->webAdvancedInfoIssue . ')';
				}
			}
				//Add the page numbers (if provided)
			$pageholder = $this->formatJournalPageNumbers($work->webStartPage, $work->webEndPage, $work->webHasNonConsecutive, $work->webNonConsecutivePageNums);
			if ($pageholder) {
					//There are page numbers
				if ($work->printAdvancedInfoVolume || $work->printAdvancedInfoIssue) {
						//There is a volume & issue number preceeding
					$ret .= ', ' . $pageholder;
				} else {
						//There is no volume & issue number preceeding
					if ($work->magazineTitle) {
							//There is a magazine title preceeding
						$ret .= ', ' . $pageholder;
					} else {
							//There is no magazine title preceeding
						$ret .= $pageholder;
					}
				}
			}
				//Add a period
			$ret .= '. ';
				//Add the URL (if provided)
			if ($work->webUrl) {
				$ret .= 'Retrieved from ' . Utility::checkUrlPrepend($work->webUrl);
			}
		}
		if ($work->medium == "db") {
				//Add the volume and issue numbers (if provided)
			if ($work->dbAdvancedInfoVolume || $work->dbAdvancedInfoIssue) {
					//Add a comma after the magazine title (if provided)
				if ($work->magazineTitle) {
					$ret .= ', ';
				}
				$ret .= '<i>' . $work->dbAdvancedInfoVolume . '</i>';
				if ($work->dbAdvancedInfoIssue) {
						//Add the issue number (if provided)
					$ret .= '(' . $work->dbAdvancedInfoIssue . ')';
				}
			}
				//Add the page numbers (if provided)
			$pageholder = $this->formatJournalPageNumbers($work->dbStartPage, $work->dbEndPage, $work->dbHasNonConsecutive, '');
			if ($pageholder) {
					//There are page numbers
				if ($work->dbAdvancedInfoVolume || $work->dbAdvancedInfoIssue) {
						//There is a volume & issue number preceeding
					$ret .= ', ' . $pageholder;
				} else {
						//There is no volume & issue number preceeding
					if ($work->magazineTitle) {
							//There is a magazine title preceeding
						$ret .= ', ' . $pageholder;
					} else {
							//There is no magazine title preceeding
						$ret .= $pageholder;
					}
				}
			}
				//Add a period
			$ret .= '. ';
				//Add the URL (if provided)
			if ($work->dbUrl) {
				$ret .= 'Retrieved from ' . Utility::checkUrlPrepend($work->dbUrl);
			}
		}
		return $ret;
	}

	/**
	 * Creates a newspaper article citation
	 *
	 * @param Work $work
	 *
	 * @return string
	 */
	function newspaper(Work $work)
	{
			//Add the contributors
		$ret = $this->formatAuthors($work->authors);
			//Add the publishing date
		if ($work->medium == "print") {
			$ret .= $this->formatPublishDate($work->day, $work->month, $work->year) . '. ';
		}
		if ($work->medium == "website") {
			$ret .= $this->formatPublishDate($work->electronicPublishDay, $work->electronicPublishMonth, $work->electronicPublishYear) . '. ';
		}
		if ($work->medium == "db") {
			$ret .= $this->formatPublishDate($work->dbPublishedDay, $work->dbPublishedMonth, $work->dbPublishedYear) . '. ';
		}
			//Add the article title (if provided)
		if ($work->articleTitle) {
			$ret .= $this->formatTitle($work->articleTitle) . ' ';
		}
			//in print
		if ($work->medium == "print") {
				//Add the newspaper title
			$ret .= '<i>' . ucwords($work->newspaperTitle) . '</i>';
				//Add a comma after the newspaper title
			$ret .= ', ';
				//Add the page numbers
			$ret .= $this->formatNewspaperPageNumbers($work->startPage, $work->endPage, $work->hasNonConsecutivePages, $work->nonConsecutivePageNums) . '.';
		}
			//on a website
		if ($work->medium == "website") {
				//Add the newspaper title
			$ret .= '<i>' . ucwords($work->newspaperTitle) . '</i>';
				//Add a period after the newspaper title
			$ret .= '. ';
				//Add the Home page URL (if provided)
			if ($work->webUrl) {
					//Add the URL
				$ret .= 'Retrieved from ' . $work->webUrl;
			}
		}
			//in a database
		if ($work->medium == "db") {
				//Add the newspaper title
			$ret .= '<i>' . ucwords($work->newspaperTitle) . '</i>';
				//Add a period after the newspaper title
			$ret .= '. ';
				//Add the Home page URL (if provided)
			if ($work->dbUrl) {
					//Add the URL
				$ret .= 'Retrieved from ' . $work->dbUrl;
			}
		}
		return $ret;
	}

	/**
	 * Creates a scholarly journal article citation
	 *
	 * @param Work $work
	 *
	 * @return string
	 */
	function journal(Work $work)
	{
			//Add the contributors
		$ret = $this->formatAuthors($work->authors);
			//Add the publishing date (if provided)
		if ($work->yearPublished) {
			$ret .= ' (' . $work->yearPublished . '). ';
		}
			//Add the article title (if provided)
		if ($work->articleTitle) {
			$ret .= $this->formatTitle($work->articleTitle) . ' ';
		}
			//Add the journal title (if provided)
		if ($work->journalTitle) {
			$journalTitleholder = ucwords($work->journalTitle);
			$ret .= '<i>' . Utility::lowerArticles($journalTitleholder) . '</i>';
		}
			//Add the volume and issue numbers (if provided)
		if ($work->volume || $work->issue) {
				//Add a comma after the journal title (if provided)
			if ($work->journalTitle) {
				$ret .= ', ';
			}
			$ret .= '<i>' . $work->volume . '</i>';
			if ($work->issue) {
					//Add the issue number (if provided)
				$ret .= '(' . $work->issue . ')';
			}
		}
			//Add the page numbers (if provided)
		$pageholder = $this->formatJournalPageNumbers($work->startPage, $work->endPage, $work->hasNonConsecutivePages, $work->nonConsecutivePageNums);
		if ($pageholder) {
				//There are page numbers
			if ($work->volume || $work->issue) {
					//There is a volume & issue number preceeding
				$ret .= ', ' . $pageholder;
			} else {
					//There is no volume & issue number preceeding
				if ($work->journalTitle) {
						//There is a magazine title preceeding
					$ret .= ', ' . $pageholder;
				} else {
						//There is no journal title preceeding
					$ret .= $pageholder;
				}
			}
		}
			//Add a period
		$ret .= '. ';
			//on a website
		if ($work->medium == "website") {
				//Add the URL (if provided)
			if ($work->webUrl) {
				$ret .= 'Retrieved from ' . Utility::checkUrlPrepend($work->webUrl);
			} elseif ($work->webDoi) {
					//Add the DOI (if provided)
				$ret .= 'doi:' . $work->webDoi;
			}
		}
			//in a database
		if ($work->medium == "db") {
				//Add the URL (if provided)
			if ($work->dbUrl) {
				$ret .= 'Retrieved from ' . Utility::checkUrlPrepend($work->dbUrl);
			} elseif ($work->dbDoi) {
					//Add the DOI (if provided)
				$ret .= 'doi:' . $work->dbDoi;
			}
		}
		return $ret;
	}

	/**
	 * Creates a web site citation
	 *
	 * @param Work $work
	 *
	 * @return string
	 */
	function website(Work $work)
	{
			//Add the contributors
		$ret = $this->formatAuthors($work->authors);
			//Add the publishing date
		$ret .= $this->formatPublishDate($work->electronicPublishDay, $work->electronicPublishMonth, $work->electronicPublishYear) . '. ';
			//Add the article title (if provided)
		if ($work->articleTitle) {
			$ret .= $this->formatTitle($work->articleTitle) . ' ';
		}
			//Add the website title (if provided)
		if ($work->webTitle) {
			$ret .= 'Retrieved from ' . $work->webTitle . ' ';
		}
			//Add the URL (if provided)
		if ($work->webUrl) {
			$ret .= 'website: ' . Utility::checkUrlPrepend($work->webUrl);
		}
		return $ret;
	}

	function __toString() {
		return 'APA6';
	}
}
