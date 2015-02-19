<?php namespace Piestar\CitationBuilder\Styles;

use Piestar\CitationBuilder\Utility;

/**
 * American Psychological Association (APA) format
 */
class Apa6 {

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
			// if start page is less than end page and the pages are consecutive
			$ret = 'p. ' . ucwords($startPage);

			return $ret;
		}
		if ($startPage < $endPage && ! $hasNonConsecutivePages) {
			// if start page is less than end page and the pages are consecutive
			$ret = 'pp. ' . ucwords($startPage) . "-" . ucwords($endPage);

			return $ret;
		}
		if ($hasNonConsecutivePages && $nonConsecutivePageNums) {
			// if the pages are not consecutive and there are page numbers to display
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
			// if start page equals end page or there is a start page, but no end page
			$ret = ucwords($startPage);

			return $ret;
		}
		if ($startPage < $endPage && ! $hasNonConsecutivePages) {
			// if start page is less than end page and the pages are consecutive
			$ret = ucwords($startPage) . "-" . ucwords($endPage);

			return $ret;
		}
		if ($hasNonConsecutivePages && $nonConsecutivePageNums) {
			// if the pages are not consecutive and there are page numbers to display
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
		// Count the number of contributors in the array
		$count = count($authors);
		// Count the number of authors in the array
		$numAuthors = 0;
		foreach ($authors as $contributor) {
			if ($contributor['cselect'] == 'author') {
				$numAuthors ++;
			}
		}
		$ret = '';
		for ($i = 0; $i < $count; $i ++) {
			// If this contributor is an author
			if ($authors[ $i ]['cselect'] == 'author') {
				if ($i == 0) {
					// First time through the loop
					if ($numAuthors > 1) {
						// There is more than one author
						$ret .= ucwords($authors[ $i ]['lname']);
						if (($authors[ $i ]['fname'] || $authors[ $i ]['mi'])) {
							// The author is a person and not a corporation
							// Check for a hyphen in the first name
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
							// The author is a corporation and not a person
							$ret .= ', ';
						}
					} else {
						// There is only one author
						if (($authors[ $i ]['lname'] != 'Anonymous') || ( ! $authors[ $i ]['lname'] && ! $authors[ $i ]['fname'] && ! $authors[ $i ]['mi'])) {
							// The author is not Anonymous or blank
							$ret .= ucwords($authors[ $i ]['lname']);
							if (($authors[ $i ]['fname'] || $authors[ $i ]['mi'])) {
								// The author is a person and not a corporation
								// Check for a hyphen in the first name
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
								// The author is a corporation and not a person
								$ret .= '. ';
							}
						}
					}
				} elseif ($i >= 5) {
					// Sixth or more time through the loop
					if ($numAuthors > 7 && $i == 5) {
						// There are more than 7 authors and this is the sixth time through the loop
						$ret .= ' ' . ucwords($authors[ $i ]['lname']) . ', ';
						if (($authors[ $i ]['fname'] || $authors[ $i ]['mi'])) {
							// The author is a person and not a corporation
							// Check for a hyphen in the first name
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
							// The author is a corporation and not a person
							$ret .= ', . . . ';
						}
					} elseif ($numAuthors == 7 && $i == 5) {
						// There are 7 authors and this is the sixth time through the loop
						$ret .= ' ' . ucwords($authors[ $i ]['lname']);
						if (($authors[ $i ]['fname'] || $authors[ $i ]['mi'])) {
							// The author is a person and not a corporation
							// Check for a hyphen in the first name
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
							// The author is a corporation and not a person
							$ret .= ', & ';
						}
					} elseif (($i + 1) == $count) {
						// This is the last time through the loop
						// If there are 6 authors add an ampersand before the name, otherwise do not
						if ($numAuthors == 6) {
							$ret .= ' & ' . ucwords($authors[ $i ]['lname']);
							if (($authors[ $i ]['fname'] || $authors[ $i ]['mi'])) {
								// The author is a person and not a corporation
								// Check for a hyphen in the first name
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
								// The author is a corporation and not a person
								$ret .= '. ';
							}
						} else {
							$ret .= ' ' . ucwords($authors[ $i ]['lname']);
							if (($authors[ $i ]['fname'] || $authors[ $i ]['mi'])) {
								// The author is a person and not a corporation
								// Check for a hyphen in the first name
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
								// The author is a corporation and not a person
								$ret .= '. ';
							}
						}
					}
				} else {
					if (($i + 1) == $count) {
						// This is the last time through the loop
						if ($numAuthors > 1) {
							// There is more than one author
							$ret .= ' & ' . ucwords($authors[ $i ]['lname']);
							if (($authors[ $i ]['fname'] || $authors[ $i ]['mi'])) {
								// The author is a person and not a corporation
								// Check for a hyphen in the first name
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
								// The author is a corporation and not a person
								$ret .= '. ';
							}
						} else {
							// There is only one author
							if (($authors[ $i ]['lname'] != 'Anonymous') || ( ! $authors[ $i ]['lname'] && ! $authors[ $i ]['fname'] && ! $authors[ $i ]['mi'])) {
								// The author is not Anonymous or blank
								$ret .= ucwords($authors[ $i ]['lname']);
								if (($authors[ $i ]['fname'] || $authors[ $i ]['mi'])) {
									// The author is a person and not a corporation
									// Check for a hyphen in the first name
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
									// The author is a corporation and not a person
									$ret .= '. ';
								}
							}
						}
					} else {
						$ret .= ' ' . ucwords($authors[ $i ]['lname']);
						if (($authors[ $i ]['fname'] || $authors[ $i ]['mi'])) {
							// The author is a person and not a corporation
							// Check for a hyphen in the first name
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
							// The author is a corporation and not a person
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
		$count = count($translators);
		// Count the number of authors in the array
		$numAuthors = 0;
		// Count the number of translators in the array
		$numTranslators = 0;
		foreach ($translators as $contributor) {
			if ($contributor['cselect'] == 'author') {
				$numAuthors ++;
			} elseif ($contributor['cselect'] == 'translator') {
				$numTranslators ++;
			}
		}
		$ret = '';
		// Translator iterative counter
		$t = 0;
		for ($i = 0; $i < $count; $i ++) {
			if ($translators[ $i ]['cselect'] == 'translator') {
				// If this contributor is an translator
				if ($t == 0) {
					// First time through the loop
					if ($numTranslators > 1) {
						// There is more than one translator
						$ret .= '(';
						$ret .= substr(ucwords($translators[ $i ]['fname']), 0, 1) . '. ';
						if ($translators[ $i ]['mi']) {
							$ret .= ucwords($translators[ $i ]['mi']) . '. ';
						}
						$ret .= ucwords($translators[ $i ]['lname']);
						if ($numTranslators > 2) {
							// There are more than two translators
							$ret .= ',';
						}
					} else {
						// There is only one translator
						if (($translators[ $i ]['lname'] != 'Anonymous') || ( ! $translators[ $i ]['lname'] && ! $translators[ $i ]['fname'] && ! $translators[ $i ]['mi'])) {
							// The translator is not Anonymous or blank
							$ret .= '(';
							$ret .= substr(ucwords($translators[ $i ]['fname']), 0, 1) . '. ';
							if ($translators[ $i ]['mi']) {
								$ret .= ucwords($translators[ $i ]['mi']) . '. ';
							}
							$ret .= ucwords($translators[ $i ]['lname']);
						}
					}
				} elseif (($t + 1) == $numTranslators) {
					// Last time through the loop
					if ($numTranslators > 1) {
						// There is more than one translator
						$ret .= ' & ' . substr(ucwords($translators[ $i ]['fname']), 0, 1) . '. ';
						if ($translators[ $i ]['mi']) {
							$ret .= ucwords($translators[ $i ]['mi']) . '. ';
						}
						$ret .= ucwords($translators[ $i ]['lname']);
					} else {
						// There is only one translator
						if (($translators[ $i ]['lname'] != 'Anonymous') || ( ! $translators[ $i ]['lname'] && ! $translators[ $i ]['fname'] && ! $translators[ $i ]['mi'])) {
							// The translator is not Anonymous or blank
							$ret .= '(';
							$ret .= substr(ucwords($translators[ $i ]['fname']), 0, 1) . '. ';
							if ($translators[ $i ]['mi']) {
								$ret .= ucwords($translators[ $i ]['mi']) . '. ';
							}
							$ret .= ucwords($translators[ $i ]['lname']);
						}
					}
				} elseif (($t + 2) == $numTranslators) {
					// Second to last time through the loop
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
		$count = count($editors);
		// Count the number of authors in the array
		$numAuthors = 0;
		// Count the number of editors in the array
		$numEditors = 0;
		foreach ($editors as $contributor) {
			if ($contributor['cselect'] == 'author') {
				$numAuthors ++;
			} elseif ($contributor['cselect'] == 'editor') {
				$numEditors ++;
			}
		}
		$ret = '';
		// editor iterative counter
		$t = 0;
		for ($i = 0; $i < $count; $i ++) {
			if ($editors[ $i ]['cselect'] == 'editor') {
				// If this contributor is an editor
				if ($t == 0) {
					// First time through the loop
					if ($numEditors > 1) {
						// There is more than one editor
						$ret .= 'In ';
						if ($editors[ $i ]['fname']) {
							$ret .= substr(ucwords($editors[ $i ]['fname']), 0, 1) . '. ';
						}
						if ($editors[ $i ]['mi']) {
							$ret .= ' ' . ucwords($editors[ $i ]['mi']) . '. ';
						}
						$ret .= ucwords($editors[ $i ]['lname']);
						if ($numEditors > 2) {
							// There are more than two editors
							$ret .= ',';
						}
					} else {
						// There is only one editor
						$ret .= 'In ';
						if (($editors[ $i ]['lname'] != 'Anonymous') || ( ! $editors[ $i ]['lname'] && ! $editors[ $i ]['fname'] && ! $editors[ $i ]['mi'])) {
							// The editor is not Anonymous or blank
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
					// Last time through the loop
					if ($numEditors > 1) {
						// There is more than one editor
						if ($editors[ $i ]['fname']) {
							$ret .= ' & ' . substr(ucwords($editors[ $i ]['fname']), 0, 1) . '. ';
						}
						if ($editors[ $i ]['mi']) {
							$ret .= ' ' . ucwords($editors[ $i ]['mi']) . '. ';
						}
						$ret .= ucwords($editors[ $i ]['lname']);
					} else {
						// There is only one editor
						if (($editors[ $i ]['lname'] != 'Anonymous') || ( ! $editors[ $i ]['lname'] && ! $editors[ $i ]['fname'] && ! $editors[ $i ]['mi'])) {
							// The editor is not Anonymous or blank
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
					// Second to last time through the loop
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
	// Uppercase the first word in article title
		$articleTitle = ucfirst(strtolower($articleTitle));
	// If the article title contains a subtitle, capitalize the first word after the colon
		if (preg_match('/:[ ]+[a-z]/', $articleTitle, $matches)) {
			$articleTitle = Utility::uppercaseSubtitle($articleTitle);
		}
	// Punctuate after the article title
		$articleTitle = Utility::addPeriod($articleTitle);

		return $articleTitle;
	}

	/**
	 * Format a book title (APA)
	 *
	 * @param string $addpunctuation
	 *
	 * @return string
	 */
	function formatBookTitle($title)
	{
	// Uppercase the first word in article title
		$ret = ucfirst(strtolower($title));
	// If the article title contains a subtitle, capitalize the first word after the colon
		$ret = Utility::uppercaseSubtitle($ret);
	// Punctuate after the book title, if necessary
		$ret = Utility::addPeriod($ret);
		$ret = '<i>' . $ret . '</i>';

		return $ret;
	}

	/********************************/
	/*     Citation parsing         */
	/********************************/

	/** Creates a book (in entirety) citation */
	function citeBook($medium, $contributors, $publicationYear, $bookTitle, $publisherLocation, $publisher,
		$webUrl, $webDoi, $dbUrl, $dbDoi, $medium, $ebookUrl, $ebookDoi)
	{
		// Add the contributors
		$ret = $this->formatAuthors($contributors);
		// Add the publishing date (if provided)
		if ($publicationYear) {
			$ret .= ' (' . $publicationYear . '). ';
		}
		// Add the book title (if provided)
		if ($bookTitle) {
			$ret .= $this->formatBookTitle($bookTitle, "yes") . ' ';
		}
		// Add the translators (if provided)
		$ret .= $this->formatTranslators($contributors) . ' ';
		// Add the editors (if provided)
		$ret .= $this->formatEditors($contributors) . ' ';
		// in print
		if ($medium == "print") {
			// Add the publisher location (if provided)
			if ($publisherLocation) {
				$ret .= ucwords($publisherLocation) . ': ';
			}
			// Add the publisher (if provided)
			if ($publisher) {
				$ret .= ucwords($publisher) . '.';
			}
		}
		// on a website
		if ($medium == "website") {
			// Add the URL (if provided)
			if ($webUrl) {
				$ret .= 'Retrieved from ' . Utility::checkUrlPrepend($webUrl);
			} elseif ($webDoi) {
				// Add the DOI (if provided)
				$ret .= 'doi:' . $webDoi;
			}
		}
		// in a database
		if ($medium == "db") {
			// Add the URL (if provided)
			if ($dbUrl) {
				$ret .= 'Retrieved from ' . Utility::checkUrlPrepend($dbUrl);
			} elseif ($dbDoi) {
				// Add the DOI (if provided)
				$ret .= 'doi:' . $dbDoi;
			}
		}
		// as an ebook
		if ($medium == "ebook") {
			// Add the URL (if provided)
			if ($ebookUrl) {
				$ret .= 'Retrieved from ' . Utility::checkUrlPrepend($ebookUrl);
			} elseif ($ebookDoi) {
				// Add the DOI (if provided)
				$ret .= 'doi:' . $ebookDoi;
			}
		}
		echo $ret;
	}

	/** Creates a chapter or essay from a book citation */
	function siteChapterEssay($medium, $contributors, $publicationYear, $chapterEssay, $bookTitle,
		$startPage, $endPage, $hasNonConsecutivePages, $nonConsecutivePageNums, $publisherLocation, $publisher,
		$webUrl, $webDoi, $dbUrl, $dbDoi)
	{
		// Add the contributors
		$ret = $this->formatAuthors($contributors);
		// Add the publishing date (if provided)
		if ($publicationYear) {
			$ret .= ' (' . $publicationYear . '). ';
		}
		// Add the chapter/essay title (if provided)
		if ($chapterEssay) {
			$ret .= $this->formatTitle($chapterEssay) . ' ';
		}
		// Add the translators (if provided)
		$ret .= $this->formatTranslators($contributors) . ' ';
		// Add the editors (if provided)
		if ($this->formatEditors($contributors)) {
			$ret .= $this->formatEditors($contributors) . ' ';
		} else {
			$ret .= 'In ';
		}
		// Add the book title and page numbers (if provided)
		$pageholder = $this->formatNewspaperPageNumbers($startPage, $endPage, $hasNonConsecutivePages, $nonConsecutivePageNums);
		if ($pageholder) {
			// There are page numbers to display
			if ($bookTitle) {
				// There is a book title to display
				$ret .= $this->formatBookTitle($bookTitle, "no") . ' ';
			}
			$ret .= '(' . $pageholder . '). ';
		} else {
			// There are no page numbers to display
			if ($bookTitle) {
				// There is a book title to display
				$ret .= $this->formatBookTitle($bookTitle, "yes") . ' ';
			}
		}
		// Add the publisher location (if provided)
		if ($publisherLocation) {
			$ret .= ucwords($publisherLocation) . ': ';
		}
		// Add the publisher (if provided)
		if ($publisher) {
			$ret .= ucwords($publisher) . '. ';
		}
		// on a website
		if ($medium == "website") {
			// Add the URL (if provided)
			if ($webUrl) {
				$ret .= 'Retrieved from ' . Utility::checkUrlPrepend($webUrl);
			} elseif ($webDoi) {
				// Add the DOI (if provided)
				$ret .= 'doi:' . $webDoi;
			}
		}
		// in a database
		if ($medium == "db") {
			// Add the URL (if provided)
			if ($dbUrl) {
				$ret .= 'Retrieved from ' . Utility::checkUrlPrepend($dbUrl);
			} elseif ($dbDoi) {
				// Add the DOI (if provided)
				$ret .= 'doi:' . $dbDoi;
			}
		}
		echo $ret;
	}

	/** Creates a magazine article citation */
	function citeMagazine($medium, $contributors, $articleTitle, $magazineTitle, $day, $month, $year,
		$startPage, $endPage, $hasNonConsecutivePages, $nonConsecutivePageNums, $printAdvancedInfoVolume, $printAdvancedInfoIssue,
		$webStartPage, $webEndPage, $webHasNonConsecutive, $webNonConsecutivePageNums, $webAdvancedInfoVolume, $webAdvancedInfoIssue,
		$webUrl, $dbStartPage, $dbEndPage, $dbHasNonConsecutive, $dbAdvancedInfoVolume, $dbAdvancedInfoIssue, $dbUrl)
	{
		// Add the contributors
		$ret = $this->formatAuthors($contributors);
		// Add the publishing date
		$ret .= $this->formatPublishDate($day, $month, $year) . '. ';
		// Add the article title (if provided)
		if ($articleTitle) {
			$ret .= $this->formatTitle($articleTitle) . ' ';
		}
		// Add the magazine title (if provided)
		if ($magazineTitle) {
			$magtitleholder = ucwords($magazineTitle);
			$ret .= '<i>' . Utility::lowerArticles($magtitleholder) . '</i>';
		}
		if ($medium == "print") {
			// Add the volume and issue numbers (if provided)
			if ($printAdvancedInfoVolume || $printAdvancedInfoIssue) {
				// Add a comma after the magazine title (if provided)
				if ($magazineTitle) {
					$ret .= ', ';
				}
				$ret .= '<i>' . $printAdvancedInfoVolume . '</i>';
				if ($printAdvancedInfoIssue) {
					// Add the issue number (if provided)
					$ret .= '(' . $printAdvancedInfoIssue . ')';
				}
			}
			// Add the page numbers (if provided)
			$pageholder = $this->formatJournalPageNumbers($startPage, $endPage, $hasNonConsecutivePages, $nonConsecutivePageNums);
			if ($pageholder) {
				// There are page numbers
				if ($printAdvancedInfoVolume || $printAdvancedInfoIssue) {
					// There is a volume & issue number preceeding
					$ret .= ', ' . $pageholder;
				} else {
					// There is no volume & issue number preceeding
					if ($magazineTitle) {
						// There is a magazine title preceeding
						$ret .= ', ' . $pageholder;
					} else {
						// There is no magazine title preceeding
						$ret .= $pageholder;
					}
				}
			}
			// Add a period
			$ret .= '. ';
		}
		if ($medium == "website") {
			// Add the volume and issue numbers (if provided)
			if ($webAdvancedInfoVolume || $webAdvancedInfoIssue) {
				// Add a comma after the magazine title (if provided)
				if ($magazineTitle) {
					$ret .= ', ';
				}
				$ret .= '<i>' . $webAdvancedInfoVolume . '</i>';
				if ($webAdvancedInfoIssue) {
					// Add the issue number (if provided)
					$ret .= '(' . $webAdvancedInfoIssue . ')';
				}
			}
			// Add the page numbers (if provided)
			$pageholder = $this->formatJournalPageNumbers($webStartPage, $webEndPage, $webHasNonConsecutive, $webNonConsecutivePageNums);
			if ($pageholder) {
				// There are page numbers
				if ($printAdvancedInfoVolume || $printAdvancedInfoIssue) {
					// There is a volume & issue number preceeding
					$ret .= ', ' . $pageholder;
				} else {
					// There is no volume & issue number preceeding
					if ($magazineTitle) {
						// There is a magazine title preceeding
						$ret .= ', ' . $pageholder;
					} else {
						// There is no magazine title preceeding
						$ret .= $pageholder;
					}
				}
			}
			// Add a period
			$ret .= '. ';
			// Add the URL (if provided)
			if ($webUrl) {
				$ret .= 'Retrieved from ' . Utility::checkUrlPrepend($webUrl);
			}
		}
		if ($medium == "db") {
			// Add the volume and issue numbers (if provided)
			if ($dbAdvancedInfoVolume || $dbAdvancedInfoIssue) {
				// Add a comma after the magazine title (if provided)
				if ($magazineTitle) {
					$ret .= ', ';
				}
				$ret .= '<i>' . $dbAdvancedInfoVolume . '</i>';
				if ($dbAdvancedInfoIssue) {
					// Add the issue number (if provided)
					$ret .= '(' . $dbAdvancedInfoIssue . ')';
				}
			}
			// Add the page numbers (if provided)
			$pageholder = $this->formatJournalPageNumbers($dbStartPage, $dbEndPage, $dbHasNonConsecutive, '');
			if ($pageholder) {
				// There are page numbers
				if ($dbAdvancedInfoVolume || $dbAdvancedInfoIssue) {
					// There is a volume & issue number preceeding
					$ret .= ', ' . $pageholder;
				} else {
					// There is no volume & issue number preceeding
					if ($magazineTitle) {
						// There is a magazine title preceeding
						$ret .= ', ' . $pageholder;
					} else {
						// There is no magazine title preceeding
						$ret .= $pageholder;
					}
				}
			}
			// Add a period
			$ret .= '. ';
			// Add the URL (if provided)
			if ($dbUrl) {
				$ret .= 'Retrieved from ' . Utility::checkUrlPrepend($dbUrl);
			}
		}
		echo $ret;
	}

	/** Creates a newspaper article citation */
	function citeNewspaper($medium, $contributors, $articleTitle, $newspaperTitle, $day, $month, $year,
		$startPage, $endPage, $hasNonConsecutivePages, $nonConsecutivePageNums, $webUrl,
		$electronicPublishDay, $electronicPublishMonth, $electronicPublishYear,
		$dbPublishedDay, $dbPublishedMonth, $dbPublishedYear, $dbUrl)
	{
		// Add the contributors
		$ret = $this->formatAuthors($contributors);
		// Add the publishing date
		if ($medium == "print") {
			$ret .= $this->formatPublishDate($day, $month, $year) . '. ';
		}
		if ($medium == "website") {
			$ret .= $this->formatPublishDate($electronicPublishDay, $electronicPublishMonth, $electronicPublishYear) . '. ';
		}
		if ($medium == "db") {
			$ret .= $this->formatPublishDate($dbPublishedDay, $dbPublishedMonth, $dbPublishedYear) . '. ';
		}
		// Add the article title (if provided)
		if ($articleTitle) {
			$ret .= $this->formatTitle($articleTitle) . ' ';
		}
		// in print
		if ($medium == "print") {
			// Add the newspaper title
			$ret .= '<i>' . ucwords($newspaperTitle) . '</i>';
			// Add a comma after the newspaper title
			$ret .= ', ';
			// Add the page numbers
			$ret .= $this->formatNewspaperPageNumbers($startPage, $endPage, $hasNonConsecutivePages, $nonConsecutivePageNums) . '.';
		}
		// on a website
		if ($medium == "website") {
			// Add the newspaper title
			$ret .= '<i>' . ucwords($newspaperTitle) . '</i>';
			// Add a period after the newspaper title
			$ret .= '. ';
			// Add the Home page URL (if provided)
			if ($webUrl) {
				// Add the URL
				$ret .= 'Retrieved from ' . $webUrl;
			}
		}
		// in a database
		if ($medium == "db") {
			// Add the newspaper title
			$ret .= '<i>' . ucwords($newspaperTitle) . '</i>';
			// Add a period after the newspaper title
			$ret .= '. ';
			// Add the Home page URL (if provided)
			if ($dbUrl) {
				// Add the URL
				$ret .= 'Retrieved from ' . $dbUrl;
			}
		}
		echo $ret;
	}

	/** Creates a scholarly journal article citation */
	function citeJournal($medium, $contributors, $yearPublished, $articleTitle, $journalTitle,
		$volume, $issue, $startPage, $endPage, $hasNonConsecutivePages, $nonConsecutivePageNums, $webUrl, $webDoi,
		$dbUrl, $dbDoi)
	{
		// Add the contributors
		$ret = $this->formatAuthors($contributors);
		// Add the publishing date (if provided)
		if ($yearPublished) {
			$ret .= ' (' . $yearPublished . '). ';
		}
		// Add the article title (if provided)
		if ($articleTitle) {
			$ret .= $this->formatTitle($articleTitle) . ' ';
		}
		// Add the journal title (if provided)
		if ($journalTitle) {
			$journalTitleholder = ucwords($journalTitle);
			$ret .= '<i>' . Utility::lowerArticles($journalTitleholder) . '</i>';
		}
		// Add the volume and issue numbers (if provided)
		if ($volume || $issue) {
			// Add a comma after the journal title (if provided)
			if ($journalTitle) {
				$ret .= ', ';
			}
			$ret .= '<i>' . $volume . '</i>';
			if ($issue) {
				// Add the issue number (if provided)
				$ret .= '(' . $issue . ')';
			}
		}
		// Add the page numbers (if provided)
		$pageholder = $this->formatJournalPageNumbers($startPage, $endPage, $hasNonConsecutivePages, $nonConsecutivePageNums);
		if ($pageholder) {
			// There are page numbers
			if ($volume || $issue) {
				// There is a volume & issue number preceeding
				$ret .= ', ' . $pageholder;
			} else {
				// There is no volume & issue number preceeding
				if ($journalTitle) {
					// There is a magazine title preceeding
					$ret .= ', ' . $pageholder;
				} else {
					// There is no journal title preceeding
					$ret .= $pageholder;
				}
			}
		}
		// Add a period
		$ret .= '. ';
		// on a website
		if ($medium == "website") {
			// Add the URL (if provided)
			if ($webUrl) {
				$ret .= 'Retrieved from ' . Utility::checkUrlPrepend($webUrl);
			} elseif ($webDoi) {
				// Add the DOI (if provided)
				$ret .= 'doi:' . $webDoi;
			}
		}
		// in a database
		if ($medium == "db") {
			// Add the URL (if provided)
			if ($dbUrl) {
				$ret .= 'Retrieved from ' . Utility::checkUrlPrepend($dbUrl);
			} elseif ($dbDoi) {
				// Add the DOI (if provided)
				$ret .= 'doi:' . $dbDoi;
			}
		}
		echo $ret;
	}

	/** Creates a web site citation */
	function citeWebsite($contributors, $articleTitle, $webTitle, $webUrl,
		$electronicPublishDay, $electronicPublishMonth, $electronicPublishYear)
	{
		// Add the contributors
		$ret = $this->formatAuthors($contributors);
		// Add the publishing date
		$ret .= $this->formatPublishDate($electronicPublishDay, $electronicPublishMonth, $electronicPublishYear) . '. ';
		// Add the article title (if provided)
		if ($articleTitle) {
			$ret .= $this->formatTitle($articleTitle) . ' ';
		}
		// Add the website title (if provided)
		if ($webTitle) {
			$ret .= 'Retrieved from ' . $webTitle . ' ';
		}
		// Add the URL (if provided)
		if ($webUrl) {
			$ret .= 'website: ' . Utility::checkUrlPrepend($webUrl);
		}
		echo $ret;
	}
}
