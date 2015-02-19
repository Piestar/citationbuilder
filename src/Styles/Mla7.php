<?php namespace Piestar\CitationBuilder\Styles;

use Piestar\CitationBuilder\Utility;

/**
 * Modern Language Asoociation (MLA) format
 */
class Mla7 {

	/**
	 * Format a date published (MLA)
	 *
	 * @param $day
	 * @param $month
	 * @param $year
	 *
	 * @return string
	 */
	function formatPublishedDate($day, $month, $year)
	{
		$ret = '';
		if ( ! $day && ! $month && ! $year) {
			$ret .= 'n.d';
		} else {
			if (Utility::showDay($month)) {
				$ret = $day . " ";
			}
			$ret .= $this->shortenMonth($month) . " ";
			$ret .= $year;
		}

		return $ret;
	}

	/**
	 * Format an access date for website or database (MLA)
	 *
	 * @param $day
	 * @param $month
	 * @param $year
	 *
	 * @return string
	 */
	function formatAccessDate($day, $month, $year)
	{
		$ret = '';
		if (Utility::showDay($month)) {
			$ret = $day . " ";
		}
		$ret .= $this->shortenMonth($month) . " ";
		$ret .= $year;

		return $ret;
	}

	/**
	 * Shorten a full month name into an abbreviation (MLA)
	 *
	 * @param string $month
	 *
	 * @return string
	 */
	function shortenMonth($month)
	{
		switch ($month) {
			case "January":
				$month = "Jan.";
				break;
			case "February":
				$month = "Feb.";
				break;
			case "March":
				$month = "Mar.";
				break;
			case "April":
				$month = "Apr.";
				break;
			case "May":
				$month = "May";
				break;
			case "June":
				$month = "June";
				break;
			case "July":
				$month = "July";
				break;
			case "August":
				$month = "Aug.";
				break;
			case "September":
				$month = "Sept.";
				break;
			case "October":
				$month = "Oct.";
				break;
			case "November":
				$month = "Nov.";
				break;
			case "December":
				$month = "Dec.";
				break;
		}

		return $month;
	}

	/**
	 * Ensure that ed. is at the end of edition (MLA)
	 *
	 * @param string $edition
	 *
	 * @return mixed|string
	 */
	function abbreviateEdition($edition)
	{
		$ret = preg_replace("/edition/", "ed.", $edition);
		$ret = preg_replace("/ed/", "ed.", $ret);
		$ret = preg_replace("/ed../", "ed.", $ret);
		if ( ! preg_match("/ed./", $ret) && $ret != "") {
			$ret = $ret . " ed.";
		}

		return $ret;
	}

	/** Creates the page number output (MLA)
	 *
	 * @param int $startPage
	 * @param int $endPage
	 * @param bool $hasNonConsecutivePages
	 *
	 * @return string
	 */
	function getPageNumbers($startPage, $endPage, $hasNonConsecutivePages)
	{
		if ( ! $startPage && ! $endPage && ! $hasNonConsecutivePages) {
			//There are no page numbers
			$ret = "N. pag. ";

			return $ret;
		} elseif (($startPage == $endPage) || ($startPage && ! $endPage)) {
			//The article is only on one page
			$ret = ucwords($startPage) . ". ";

			return $ret;
		}
		if ($startPage < $endPage && ! $hasNonConsecutivePages) {
			//There is a consecutive range of pages
			$ret = ucwords($startPage) . "-" . ucwords($endPage) . ". ";

			return $ret;
		}
		if ($hasNonConsecutivePages) {
			//There are nonconsecutive pages
			$ret = ucwords($startPage) . "+. ";

			return $ret;
		}
	}

	/**
	 * Format section number for a newspaper citing (MLA)
	 *
	 * @param string $section
	 *
	 * @return string
	 */
	function formatNewspaperSection($section)
	{
		if (ctype_alpha($section)) {
			$ret = $section . ' sec.';
		} else {
			$ret = 'sec. ' . $section;
		}

		return $ret;
	}

	/** Format the author/editor names (MLA)
	 *
	 * @param array $contributors
	 *
	 * @return string
	 */
	function formatContributors($contributors)
	{
		$count = count($contributors);
		//Count the number of authors in the array
		$numAuthors = 0;
		//Count the number of editors in the array
		$numEditors = 0;
		foreach ($contributors as $contributor) {
			if ($contributor['cselect'] == 'author') {
				$numAuthors ++;
			} elseif ($contributor['cselect'] == 'editor') {
				$numEditors ++;
			}
		}
		$ret = '';
		for ($i = 0; $i < $count; $i ++) {
			if ($contributors[ $i ]['cselect'] == 'author') {
				//If this contributor is an author
				if ($i == 0) {
					//First time through the loop
					if ($numAuthors > 1) {
						//There is more than one author
						$ret .= ucwords($contributors[ $i ]['lname']);
						if (($contributors[ $i ]['fname'] || $contributors[ $i ]['mi'])) {
							//The author is a person and not a corporation
							$ret .= ', ' . ucwords($contributors[ $i ]['fname']);
							if ($contributors[ $i ]['mi']) {
								$ret .= ' ' . ucwords($contributors[ $i ]['mi']) . '.';
							}
						}
						$ret .= ',';
					} else {
						//There is only one author
						if (($contributors[ $i ]['lname'] != 'Anonymous') || ( ! $contributors[ $i ]['lname'] && ! $contributors[ $i ]['fname'] && ! $contributors[ $i ]['mi'])) {
							//The author is not Anonymous or blank
							$ret .= ucwords($contributors[ $i ]['lname']);
							if (($contributors[ $i ]['fname'] || $contributors[ $i ]['mi'])) {
								//The author is a person and not a corporation
								$ret .= ', ' . ucwords($contributors[ $i ]['fname']);
								if ($contributors[ $i ]['mi']) {
									$ret .= ' ' . ucwords($contributors[ $i ]['mi']);
								}
							}
							$ret .= '. ';
						}
					}
				} elseif (($i + 1) == $count) {
					//Last time through the loop
					if ($numAuthors > 1) {
						//There is more than one author
						$ret .= ' and ' . ucwords($contributors[ $i ]['fname']) . ' ';
						if ($contributors[ $i ]['mi']) {
							$ret .= ucwords($contributors[ $i ]['mi']) . '. ';
						}
						$ret .= ucwords($contributors[ $i ]['lname']) . '. ';
					} else {
						//There is only one author
						if (($contributors[ $i ]['lname'] != 'Anonymous') || ( ! $contributors[ $i ]['lname'] && ! $contributors[ $i ]['fname'] && ! $contributors[ $i ]['mi'])) {
							//The author is not Anonymous or blank
							$ret .= ucwords($contributors[ $i ]['lname']) . ', ';
							$ret .= ucwords($contributors[ $i ]['fname']);
							if ($contributors[ $i ]['mi']) {
								$ret .= ' ' . ucwords($contributors[ $i ]['mi']);
							}
							$ret .= '. ';
						}
					}
				} else {
					$ret .= ' ' . ucwords($contributors[ $i ]['fname']) . ' ';
					if ($contributors[ $i ]['mi']) {
						$ret .= ucwords($contributors[ $i ]['mi']) . '. ';
					}
					$ret .= ucwords($contributors[ $i ]['lname']) . ',';
				}
			} elseif (($contributors[ $i ]['cselect'] == 'editor' && $numAuthors == 0)) {
				//If this contributor is an editor and there are no authors listed
				if ($i == 0) {
					//First time through the loop
					if ($numEditors > 1) {
						//There is more than one editor
						$ret .= ucwords($contributors[ $i ]['lname']);
						if (($contributors[ $i ]['fname'] || $contributors[ $i ]['mi'])) {
							//The editor is a person and not a corporation
							$ret .= ', ' . ucwords($contributors[ $i ]['fname']);
							if ($contributors[ $i ]['mi']) {
								$ret .= ' ' . ucwords($contributors[ $i ]['mi']) . '.';
							}
						}
						if ($numEditors > 2) {
							$ret .= ',';
						}
					} else {
						//There is only one editor
						if (($contributors[ $i ]['lname'] != 'Anonymous') || ( ! $contributors[ $i ]['lname'] && ! $contributors[ $i ]['fname'] && ! $contributors[ $i ]['mi'])) {
							//The editor is not Anonymous or blank
							$ret .= ucwords($contributors[ $i ]['lname']);
							if (($contributors[ $i ]['fname'] || $contributors[ $i ]['mi'])) {
								//The editor is a person and not a corporation
								$ret .= ', ' . ucwords($contributors[ $i ]['fname']);
								if ($contributors[ $i ]['mi']) {
									$ret .= ' ' . ucwords($contributors[ $i ]['mi']);
								}
							}
							$ret .= ', ed. ';
						}
					}
				} elseif (($i + 1) == $count) {
					//Last time through the loop
					if ($numEditors > 1) {
						//There is more than one editor
						$ret .= ' and ' . ucwords($contributors[ $i ]['fname']) . ' ';
						if ($contributors[ $i ]['mi']) {
							$ret .= ucwords($contributors[ $i ]['mi']) . '. ';
						}
						$ret .= ucwords($contributors[ $i ]['lname']) . ', eds. ';
					} else {
						//There is only one editor
						if (($contributors[ $i ]['lname'] != 'Anonymous') || ( ! $contributors[ $i ]['lname'] && ! $contributors[ $i ]['fname'] && ! $contributors[ $i ]['mi'])) {
							//The editor is not Anonymous or blank
							$ret .= ucwords($contributors[ $i ]['lname']) . ', ';
							$ret .= ucwords($contributors[ $i ]['fname']);
							if ($contributors[ $i ]['mi']) {
								$ret .= ' ' . ucwords($contributors[ $i ]['mi']);
							}
							$ret .= ', ed. ';
						}
					}
				} else {
					$ret .= ' ' . ucwords($contributors[ $i ]['fname']) . ' ';
					if ($contributors[ $i ]['mi']) {
						$ret .= ucwords($contributors[ $i ]['mi']) . '. ';
					}
					$ret .= ucwords($contributors[ $i ]['lname']) . ',';
				}
			}
		}

		return $ret;
	}

	/** Format the translator names (MLA)
	 *
	 * @param array $contributors
	 *
	 * @return string
	 */
	function formatTranslators($contributors)
	{
		$count = count($contributors);
		//Count the number of authors in the array
		$numAuthors = 0;
		//Count the number of translators in the array
		$numTranslators = 0;
		foreach ($contributors as $contributor) {
			if ($contributor['cselect'] == 'author') {
				$numAuthors ++;
			} elseif ($contributor['cselect'] == 'translator') {
				$numTranslators ++;
			}
		}
		$ret = '';
		//Translator iterative counter
		$t = 0;
		for ($i = 0; $i < $count; $i ++) {
			if ($contributors[ $i ]['cselect'] == 'translator') {
				//If this contributor is an translator
				if ($t == 0) {
					//First time through the loop
					if ($numTranslators > 1) {
						//There is more than one translator
						$ret .= 'Trans. ';
						$ret .= ucwords($contributors[ $i ]['fname']) . ' ';
						if ($contributors[ $i ]['mi']) {
							$ret .= ucwords($contributors[ $i ]['mi']) . ' ';
						}
						$ret .= ucwords($contributors[ $i ]['lname']);
						//If there are more than two translators, add a comma after the name
						if ($numTranslators > 2) {
							$ret .= ',';
						}
					} else {
						//There is only one translator
						if (($contributors[ $i ]['lname'] != 'Anonymous') || ( ! $contributors[ $i ]['lname'] && ! $contributors[ $i ]['fname'] && ! $contributors[ $i ]['mi'])) {
							//The translator is not Anonymous or blank
							$ret .= 'Trans. ';
							$ret .= ucwords($contributors[ $i ]['fname']) . ' ';
							if ($contributors[ $i ]['mi']) {
								$ret .= ucwords($contributors[ $i ]['mi']) . ' ';
							}
							$ret .= ucwords($contributors[ $i ]['lname']) . '. ';
						}
					}
				} elseif (($t + 1) == $numTranslators) {
					//Last time through the loop
					if ($numTranslators > 1) {
						//There is more than one translator
						$ret .= ' and ' . ucwords($contributors[ $i ]['fname']) . ' ';
						if ($contributors[ $i ]['mi']) {
							$ret .= ucwords($contributors[ $i ]['mi']) . ' ';
						}
						$ret .= ucwords($contributors[ $i ]['lname']) . '. ';
					} else {
						//There is only one translator
						if (($contributors[ $i ]['lname'] != 'Anonymous') || ( ! $contributors[ $i ]['lname'] && ! $contributors[ $i ]['fname'] && ! $contributors[ $i ]['mi'])) {
							//The translator is not Anonymous or blank
							$ret .= 'Trans. ';
							$ret .= ucwords($contributors[ $i ]['fname']) . ' ';
							if ($contributors[ $i ]['mi']) {
								$ret .= ucwords($contributors[ $i ]['mi']) . ' ';
							}
							$ret .= ucwords($contributors[ $i ]['lname']) . '. ';
						}
					}
				} else {
					$ret .= ' ' . ucwords($contributors[ $i ]['fname']) . ' ';
					if ($contributors[ $i ]['mi']) {
						$ret .= ucwords($contributors[ $i ]['mi']) . ' ';
					}
					$ret .= ucwords($contributors[ $i ]['lname']) . ',';
				}
				$t ++;
			}
		}

		return $ret;
	}

	/**
	 * Format the editor names, if there is an author (MLA)
	 *
	 * @param $contributors
	 *
	 * @return string
	 */
	function formatEditors($contributors)
	{
		$count = count($contributors);
		//Count the number of authors in the array
		$numAuthors = 0;
		//Count the number of editors in the array
		$numEditors = 0;
		foreach ($contributors as $contributor) {
			if ($contributor['cselect'] == 'author') {
				$numAuthors ++;
			} elseif ($contributor['cselect'] == 'editor') {
				$numEditors ++;
			}
		}
		$ret = '';
		//editor iterative counter
		$t = 0;
		for ($i = 0; $i < $count; $i ++) {
			if (($contributors[ $i ]['cselect'] == 'editor') && ($numAuthors != 0)) {
				//If this contributor is an editor and there are no authors
				if ($t == 0) {
					//First time through the loop
					if ($numEditors > 1) {
						//There is more than one editor
						$ret .= 'Ed. ';
						$ret .= ucwords($contributors[ $i ]['fname']);
						if ($contributors[ $i ]['mi']) {
							$ret .= ' ' . ucwords($contributors[ $i ]['mi']) . ' ';
						}
						$ret .= ucwords($contributors[ $i ]['lname']);
						//If there are more than two editors, add a comma after the name
						if ($numEditors > 2) {
							$ret .= ',';
						}
					} else {
						//There is only one editor
						if (($contributors[ $i ]['lname'] != 'Anonymous') || ( ! $contributors[ $i ]['lname'] && ! $contributors[ $i ]['fname'] && ! $contributors[ $i ]['mi'])) {
							//The editor is not Anonymous or blank
							$ret .= 'Ed. ';
							$ret .= ucwords($contributors[ $i ]['fname']) . ' ';
							if ($contributors[ $i ]['mi']) {
								$ret .= ' ' . ucwords($contributors[ $i ]['mi']) . ' ';
							}
							$ret .= ucwords($contributors[ $i ]['lname']) . '. ';
						}
					}
				} elseif (($t + 1) == $numEditors) {
					//Last time through the loop
					if ($numEditors > 1) {
						//There is more than one editor
						$ret .= ' and ' . ucwords($contributors[ $i ]['fname']) . ' ';
						if ($contributors[ $i ]['mi']) {
							$ret .= ucwords($contributors[ $i ]['mi']) . ' ';
						}
						$ret .= ucwords($contributors[ $i ]['lname']) . '. ';
					} else {
						//There is only one editor
						if (($contributors[ $i ]['lname'] != 'Anonymous') || ( ! $contributors[ $i ]['lname'] && ! $contributors[ $i ]['fname'] && ! $contributors[ $i ]['mi'])) {
							//The editor is not Anonymous or blank
							$ret .= 'Ed. ';
							$ret .= ucwords($contributors[ $i ]['fname']);
							if ($contributors[ $i ]['mi']) {
								$ret .= ' ' . ucwords($contributors[ $i ]['mi']) . ' ';
							}
							$ret .= ucwords($contributors[ $i ]['lname']) . '. ';
						}
					}
				} else {
					$ret .= ' ' . ucwords($contributors[ $i ]['fname']) . ' ';
					if ($contributors[ $i ]['mi']) {
						$ret .= ucwords($contributors[ $i ]['mi']) . ' ';
					}
					$ret .= ucwords($contributors[ $i ]['lname']) . ',';
				}
				$t ++;
			}
		}

		return $ret;
	}

	/** Format a scholarly journal year published (MLA) */
	function formatYearPublished($year)
	{
		return '(' . $year . '): ';
	}

	/**
	 * Format a book title (MLA)
	 *
	 * @param string $title
	 *
	 * @return string
	 */
	function formatBookTitle($title)
	{
		//Uppercase the words in book title
		$ret = ucwords($title);
		//Lowercase all articles, prepositions, & conjunctions
		$ret = Utility::lowerArticles($ret);
		//If the article title contains a subtitle, capitalize the first word after the colon
		if (preg_match('/:[ ]+[a-z]/', $ret, $matches)) {
			$ret = Utility::uppercaseSubtitle($ret);
		}
		//Punctuate after the book title, if necessary
		$ret = Utility::addPeriod($ret);

		return '<i>' . $ret . '</i>';
	}

	/** Format an eBook medium (MLA) */
	function formatEbookMedium($medium)
	{
		if (preg_match('/[ ]+file/', $medium, $regs)) {
			//has the word "file" at the end of the string
			$ret = $medium;
		} elseif ( ! $medium) {
			//the Medium field is blank
			$ret = '<i>Digital file</i>';
		} else {
			//does not have the word "file" at the end of the string
			$ret = $medium . ' file';
		}

		return $ret;
	}

	/********************************/
	/*     Citation parsing         */
	/********************************/

	/** Creates a book citation */
	function citeBook($medium, $contributors, $publicationYear, $title, $publisherLocation, $publisher,
		$webTitle, $webAccessDay, $webAccessMonth,  $webAccessYear, $webUrl,
		$db, $dbAccessDay, $dbAccessMonth, $dbAccessYear, $dbUrl,
		$medium, $ebookUrl)
	{
		//Add the contributors
		$ret = $this->formatContributors($contributors);
		//Add the book title (if provided)
		if ($title) {
			$ret .= $this->formatBookTitle($title) . ' ';
		}
		//Add the translators (if no authors)
		$ret .= $this->formatTranslators($contributors);
		//Add the editors (if no authors)
		$ret .= $this->formatEditors($contributors);
		//Add the publisher location (if provided)
		if ($publisherLocation) {
			$ret .= ucwords($publisherLocation) . ': ';
		}
		//Add the publisher (if provided)
		if ($publisher) {
			$ret .= ucwords($publisher) . ', ';
		}
		//Add the publication year (if provided)
		if ($publicationYear) {
			$ret .= $publicationYear . '. ';
		}
		//in print
		if ($medium == "print") {
			//Add the medium
			$ret .= 'Print.';
		}
		//on a website
		if ($medium == "website") {
			//Add the title of the website (if provided)
			if ($webTitle) {
				$ret .= '<i>' . ucwords($webTitle) . '</i>' . '. ';
			}
			//Add the medium
			$ret .= 'Web. ';
			//Add the access date (if provided)
			if ($webAccessDay || $webAccessMonth || $webAccessYear) {
				$ret .= $this->formatAccessDate($webAccessDay, $webAccessMonth, $webAccessYear) . '. ';
			}
			//Add the URL (if provided)
			if ($webUrl) {
				$ret .= '&#60;';
				$ret .= Utility::checkUrlPrepend($webUrl);
				$ret .= '&#62;';
				$ret .= '. ';
			}

		}
		//in a database
		if ($medium == "db") {
			//Add the database title (if provided)
			if ($db) {
				$ret .= '<i>' . ucwords($db) . '</i>' . '. ';
			}
			//Add the medium
			$ret .= 'Web. ';
			//Add the access date (if provided)
			if ($dbAccessDay || $dbAccessMonth || $dbAccessYear) {
				$ret .= $this->formatAccessDate($dbAccessDay, $dbAccessMonth, $dbAccessYear) . '. ';
			}
			//Add the URL (if provided)
			if ($dbUrl) {
				$ret .= '&#60;';
				$ret .= Utility::checkUrlPrepend($dbUrl);
				$ret .= '&#62;';
				$ret .= '. ';
			}
		}
		//as a digital file
		if ($medium == "ebook") {
			//Add the Medium
			$ret .= $this->formatEbookMedium($medium) . '. ';
			//Add the URL (if provided)
			if ($ebookUrl) {
				$ret .= '&#60;';
				$ret .= Utility::checkUrlPrepend($ebookUrl);
				$ret .= '&#62;';
				$ret .= '. ';
			}
		}
		echo $ret;
	}

	/** Creates a chapter or essay from a book citation */
	function siteChapter($medium, $contributors, $publicationYear,
		$chapterEssay, $bookTitle, $startPage, $endPage, $hasNonConsecutivePages,
		$publisherLocation, $publisher, $webTitle, $webAccessDay, $webAccessMonth, $webAccessYear, $webUrl,
		$db, $dbAccessDay, $dbAccessMonth, $dbAccessYear, $dbUrl)
	{
		//Add the contributors
		$ret = $this->formatContributors($contributors);
		//Add the translators (if no authors)
		$ret .= $this->formatTranslators($contributors);
		//Add the chapter/essay title (if provided)
		if ($chapterEssay) {
			//Uppercase all words in chapter/essay title, lowercase all articles, prepositions, & conjunctions, append a period, and encapsulate in double quotes
			$chapterEssay = ucwords($chapterEssay);
			$chapterEssay = Utility::lowerArticles($chapterEssay);
			$chapterEssay = Utility::addPeriod($chapterEssay);
			$ret .= '"' . $chapterEssay . '" ';
		}
		//Add the book title (if provided)
		if ($bookTitle) {
			$ret .= $this->formatBookTitle($bookTitle) . ' ';
		}
		//Add the translators (if no authors)
		$ret .= $this->formatTranslators($contributors);
		//Add the editors (if no authors)
		$ret .= $this->formatEditors($contributors);
		//Add the publisher location (if provided)
		if ($publisherLocation) {
			$ret .= ucwords($publisherLocation) . ': ';
		}
		//Add the publisher (if provided)
		if ($publisher) {
			$ret .= ucwords($publisher) . ', ';
		}
		//Add the publication year (if provided)
		if ($publicationYear) {
			$ret .= $publicationYear . '. ';
		}
		//Add the page numbers
		$ret .= $this->getPageNumbers($startPage, $endPage, $hasNonConsecutivePages);
		//in print
		if ($medium == "print") {
			//Add the medium
			$ret .= 'Print.';
		}
		//on a website
		if ($medium == "website") {
			//Add the title of the website (if provided)
			if ($webTitle) {
				$ret .= '<i>' . ucwords($webTitle) . '</i>' . '. ';
			}
			//Add the medium
			$ret .= 'Web. ';
			//Add the access date (if provided)
			if ($webAccessDay || $webAccessMonth || $webAccessYear) {
				$ret .= $this->formatAccessDate($webAccessDay, $webAccessMonth, $webAccessYear) . '. ';
			}
			//Add the URL (if provided)
			if ($webUrl) {
				$ret .= '&#60;';
				$ret .= Utility::checkUrlPrepend($webUrl);
				$ret .= '&#62;';
				$ret .= '. ';
			}

		}
		//in a database
		if ($medium == "db") {
			//Add the database title (if provided)
			if ($db) {
				$ret .= '<i>' . ucwords($db) . '</i>' . '. ';
			}
			//Add the medium
			$ret .= 'Web. ';
			//Add the access date (if provided)
			if ($dbAccessDay || $dbAccessMonth || $dbAccessYear) {
				$ret .= $this->formatAccessDate($dbAccessDay, $dbAccessMonth, $dbAccessYear) . '. ';
			}
			//Add the URL (if provided)
			if ($dbUrl) {
				$ret .= '&#60;';
				$ret .= Utility::checkUrlPrepend($dbUrl);
				$ret .= '&#62;';
				$ret .= '. ';
			}
		}
		echo $ret;
	}

	/** Creates a magazine article citation */
	function citeMagazine($medium, $contributors, $articleTitle, $magazineTitle, $publishedDay, $publishedMonth, $publishedYear,
		$startPage, $endPage, $hasNonConsecutivePages, $webTitle, $webAccessDay, $webAccessMonth, $webAccessYear, $webUrl,
		$dbStartPage, $dbEndPage, $dbHasNonConsecutive, $db, $dbAccessDay, $dbAccessMonth, $dbAccessYear, $dbUrl)
	{
		//Add the contributors
		$ret = $this->formatContributors($contributors);
		//Add the article title (if provided)
		if ($articleTitle) {
			//Uppercase all words in article title, lowercase all art., prep., & conj., append a period, and encapsulate in double quotes
			$articleTitle = ucwords($articleTitle);
			$articleTitle = Utility::lowerArticles($articleTitle);
			$articleTitle = Utility::addPeriod($articleTitle);
			$ret .= '"' . $articleTitle . '" ';
		}
		//in print
		if ($medium == "print") {
			//Add the magazine title (if provided)
			if ($magazineTitle) {
				$magtitleholder = ucwords($magazineTitle);
				$ret .= '<i>' . Utility::lowerArticles($magtitleholder) . '</i>' . ' ';
			}
			//Add the date published (if provided)
			if ($publishedDay || $publishedMonth || $publishedYear) {
				$ret .= $this->formatPublishedDate($publishedDay, $publishedMonth, $publishedYear);
				//Add a colon
				$ret .= ': ';
			}
			//Add the page numbers
			$ret .= $this->getPageNumbers($startPage, $endPage, $hasNonConsecutivePages);
			//Add the medium
			$ret .= 'Print.';
		}
		//on website
		if ($medium == "website") {
			//Add the website publisher/sponsor (if provided)
			if ($magazineTitle) {
				$ret .= '<i>' . ucwords($magazineTitle) . '</i>' . '. ';
			} else {
				$ret .= 'N.p., ';
			}
			//Add the website title (if provided)
			if ($webTitle) {
				$ret .= ucwords($webTitle) . ', ';
			}
			//Add the date published (if provided)
			$ret .= $this->formatPublishedDate($publishedDay, $publishedMonth, $publishedYear);
			//Add a period
			$ret .= '. ';
			//Add the medium
			$ret .= 'Web. ';
			//Add the access date (if provided)
			if ($webAccessDay || $webAccessMonth || $webAccessYear) {
				$ret .= $this->formatAccessDate($webAccessDay, $webAccessMonth, $webAccessYear) . '. ';
			}
			//Add the URL (if provided)
			if ($webUrl) {
				$ret .= '&#60;';
				$ret .= Utility::checkUrlPrepend($webUrl);
				$ret .= '&#62;';
				$ret .= '. ';
			}
		}
		//in a database
		if ($medium == "db") {
			//Add the magazine title (if provided)
			if ($magazineTitle) {
				$magtitleholder = ucwords($magazineTitle);
				$ret .= '<i>' . Utility::lowerArticles($magtitleholder) . '</i>' . ' ';
			}
			//Add the date published (if provided)
			$ret .= $this->formatPublishedDate($publishedDay, $publishedMonth, $publishedYear);
			//Add a period
			$ret .= '. ';
			//Add the page numbers
			$ret .= $this->getPageNumbers($dbStartPage, $dbEndPage, $dbHasNonConsecutive);
			//Add the database title (if provided)
			if ($db) {
				$ret .= '<i>' . ucwords($db) . '</i>' . '. ';
			}
			//Add the medium
			$ret .= 'Web. ';
			//Add the access date (if provided)
			if ($dbAccessDay || $dbAccessMonth || $dbAccessYear) {
				$ret .= $this->formatAccessDate($dbAccessDay, $dbAccessMonth, $dbAccessYear) . '. ';
			}
			//Add the URL (if provided)
			if ($dbUrl) {
				$ret .= '&#60;';
				$ret .= Utility::checkUrlPrepend($dbUrl);
				$ret .= '&#62;';
				$ret .= '. ';
			}
		}
		echo $ret;
	}

	/** Creates a newspaper article citation */
	function citeNewspaper($medium, $contributors, $articleTitle, $newspaperTitle, $newspaperCity,
		$day, $month, $year, $edition, $section, $startPage, $endPage, $hasNonConsecutivePages,
		$webTitle, $webUrl, $electronicPublishDay, $electronicPublishMonth, $electronicPublishYear, $webAccessDay, $webAccessMonth, $webAccessYear,
		$dbNewspaperCity, $dbPublishedDay, $dbPublishedMonth, $dbPublishedYear, $dbEdition, $dbStartPage, $dbEndPage,
		$dbHasNonConsecutive, $db, $dbAccessDay, $dbAccessMonth, $dbAccessYear, $dbUrl)
	{
		//Add the contributors
		$ret = $this->formatContributors($contributors);
		//Add the article title (if provided)
		if ($articleTitle) {
			//Uppercase all words in article title, lowercase all art., prep., & conj., append a period, and encapsulate in double quotes
			$articleTitle = ucwords($articleTitle);
			$articleTitle = Utility::lowerArticles($articleTitle);
			$articleTitle = Utility::addPeriod($articleTitle);
			$ret .= '"' . $articleTitle . '" ';
		}
		//in print
		if ($medium == "print") {
			//Add the newspaper title (if provided)
			if ($newspaperTitle) {
				//Uppercase all words in a newspaper's title
				$newspaperTitle = ucwords($newspaperTitle);
				//Remove articles (A, An, The) before the newspaper title 
				$newspaperTitle = Utility::removeArticle($newspaperTitle);
				$ret .= '<i>' . $newspaperTitle . '</i>' . ' ';
			}
			//Add the newspaper city (if provided)
			if ($newspaperCity) {
				$ret .= '[' . ucwords($newspaperCity) . ']' . ' ';
			}
			//Add the date published (if provided)
			if ($day || $month || $year) {
				$ret .= $this->formatPublishedDate($day, $month, $year);
			}
			//Add the edition (if provided)
			if ($edition) {
				$edition = strtolower($edition);
				$ret .= ', ' . $this->abbreviateEdition($edition);
			}
			//Add the section (if provided)
			if ($section) {
				$ret .= ', ' . $this->formatNewspaperSection($section);
			}
			//Add a colon
			$ret .= ': ';
			//Add the page numbers
			$ret .= $this->getPageNumbers($startPage, $endPage, $hasNonConsecutivePages);
			//Add the medium
			$ret .= 'Print.';
		}
		//on a website
		if ($medium == "website") {
			//Add the web site title (if provided)
			if ($webTitle) {
				$ret .= '<i>' . ucwords($webTitle) . '</i>' . '. ';
			}
			//Add the newspaper title (if provided)
			if ($newspaperTitle) {
				//Uppercase all words in a newspaper's title
				$newspaperTitle = ucwords($newspaperTitle);
				//Remove articles (A, An, The) before the newspaper title 
				$newspaperTitle = Utility::removeArticle($newspaperTitle);
				$ret .= '<i>' . $newspaperTitle . '</i>' . ', ';
			}
			//Add the electronically published date (if provided)
			if ($electronicPublishDay || $electronicPublishMonth || $electronicPublishYear) {
				$ret .= $this->formatPublishedDate($electronicPublishDay, $electronicPublishMonth, $electronicPublishYear) . '. ';
			}
			//Add the medium
			$ret .= 'Web. ';
			//Add the access date (if provided) 
			if ($webAccessDay || $webAccessMonth || $webAccessYear) {
				$ret .= $this->formatAccessDate($webAccessDay, $webAccessMonth, $webAccessYear) . '. ';
			}
			//Add the URL (if provided)
			if ($webUrl) {
				$ret .= '&#60;';
				$ret .= Utility::checkUrlPrepend($webUrl);
				$ret .= '&#62;';
				$ret .= '. ';
			}
		}
		//in a database
		if ($medium == "db") {
			//Add the newspaper title (if provided)
			if ($newspaperTitle) {
				//Uppercase all words in a newspaper's title
				$newspaperTitle = ucwords($newspaperTitle);
				//Remove articles (A, An, The) before the newspaper title 
				$newspaperTitle = Utility::removeArticle($newspaperTitle);
				$ret .= '<i>' . $newspaperTitle . '</i>' . ' ';
			}
			//Add the newspaper city (if provided)
			if ($dbNewspaperCity) {
				$ret .= '[' . ucwords($dbNewspaperCity) . ']' . ' ';
			}
			//Add the date published (if provided)
			if ($dbPublishedDay || $dbPublishedMonth || $dbPublishedYear) {
				$ret .= $this->formatPublishedDate($dbPublishedDay, $dbPublishedMonth, $dbPublishedYear);
			}
			//Add the edition (if provided)
			if ($dbEdition) {
				$dbEdition = strtolower($dbEdition);
				$ret .= ', ' . $this->abbreviateEdition($dbEdition);
			}
			//Add a colon
			$ret .= ': ';
			//Add the page numbers
			$ret .= $this->getPageNumbers($dbStartPage, $dbEndPage, $dbHasNonConsecutive);
			//Add the database title (if provided)
			if ($db) {
				$ret .= '<i>' . ucwords($db) . '</i>' . '. ';
			}
			//Add the medium
			$ret .= 'Web. ';
			//Add the access date
			$ret .= $this->formatAccessDate($dbAccessDay, $dbAccessMonth, $dbAccessYear) . '. ';
			//Add the URL (if provided)
			if ($dbUrl) {
				$ret .= '&#60;';
				$ret .= Utility::checkUrlPrepend($dbUrl);
				$ret .= '&#62;';
				$ret .= '. ';
			}
		}
		echo $ret;
	}

	/** Creates a scholarly journal article citation */
	function mla7scholarjournalcite($medium, $contributors, $yearPublished,
		$articleTitle, $journalTitle, $volume, $issue, $startPage, $endPage, $hasNonConsecutivePages,
		$webUrl, $webAccessDay, $webAccessMonth, $webAccessYear, $db, $dbAccessDay, $dbAccessMonth, $dbAccessYear, $dbUrl)
	{
		//Add the contributors
		$ret = $this->formatContributors($contributors);
		//Add the article title (if provided)
		if ($articleTitle) {
			//Uppercase all words in article title, lowercase all art., prep., & conj., append a period, and encapsulate in double quotes
			$articleTitle = ucwords($articleTitle);
			$articleTitle = Utility::lowerArticles($articleTitle);
			$articleTitle = Utility::addPeriod($articleTitle);
			$ret .= '"' . $articleTitle . '" ';
		}
		//Add the journal title (if provided)
		if ($journalTitle) {
			$journalTitleholder = ucwords($journalTitle);
			$ret .= '<i>' . Utility::lowerArticles($journalTitleholder) . ' </i>';
		}
		//Add the volume number (if provided)
		if ($volume) {
			$ret .= $volume;
		}
		//Add the issue number (if provided)
		if ($issue) {
			$ret .= '.' . $issue . ' ';
		}
		//Add the date published (if provided)
		if ($yearPublished) {
			$ret .= $this->formatYearPublished($yearPublished);
		}
		//Add the page numbers
		$ret .= $this->getPageNumbers($startPage, $endPage, $hasNonConsecutivePages);
		//in print
		if ($medium == "print") {
			//Add the medium
			$ret .= 'Print.';
		}
		//on a website
		if ($medium == "website") {
			//Add the medium
			$ret .= 'Web. ';
			//Add the access date (if provided)
			if ($webAccessDay || $webAccessMonth || $webAccessYear) {
				$ret .= $this->formatAccessDate($webAccessDay, $webAccessMonth, $webAccessYear) . '. ';
			}
			//Add the URL (if provided)
			if ($webUrl) {
				$ret .= '&#60;';
				$ret .= Utility::checkUrlPrepend($webUrl);
				$ret .= '&#62;';
				$ret .= '. ';
			}
		}
		//in a database
		if ($medium == "db") {
			//Add the database title (if provided)
			if ($db) {
				$ret .= '<i>' . ucwords($db) . '</i>' . '. ';
			}
			//Add the medium
			$ret .= 'Web. ';
			//Add the access date (if provided)
			if ($dbAccessDay || $dbAccessMonth || $dbAccessYear) {
				$ret .= $this->formatAccessDate($dbAccessDay, $dbAccessMonth, $dbAccessYear) . '. ';
			}
			//Add the URL (if provided)
			if ($dbUrl) {
				$ret .= '&#60;';
				$ret .= Utility::checkUrlPrepend($dbUrl);
				$ret .= '&#62;';
				$ret .= '. ';
			}
		}
		echo $ret;
	}

	/** Creates a web site citation */
	function citeWebsite($contributors, $articleTitle, $webTitle, $publisherSponsor,
		$webUrl, $electronicPublishDay, $electronicPublishMonth, $electronicPublishYear, $webAccessDay, $webAccessMonth, $webAccessYear)
	{
		//Add the contributors
		$ret = $this->formatContributors($contributors);
		//Add the article title (if provided)
		if ($articleTitle) {
			//Uppercase all words in article title, lowercase all art., prep., & conj., append a period, and encapsulate in double quotes
			$articleTitle = ucwords($articleTitle);
			$articleTitle = Utility::lowerArticles($articleTitle);
			$articleTitle = Utility::addPeriod($articleTitle);
			$ret .= '"' . $articleTitle . '" ';
		}
		//Add the web site title (if provided)
		if ($webTitle) {
			$ret .= '<i>' . ucwords($webTitle) . '</i>' . '. ';
		}
		//Add the web site publisher/sponsor (if provided)
		if ($publisherSponsor) {
			$ret .= ucwords($publisherSponsor) . ', ';
		} else {
			$ret .= 'N.p., ';
		}
		//Add the electronically published date (if provided)
		$ret .= $this->formatPublishedDate($electronicPublishDay, $electronicPublishMonth, $electronicPublishYear);
		//Add a period
		$ret .= '. ';
		//Add the medium
		$ret .= 'Web. ';
		//Add the access date (if provided)
		if ($webAccessDay || $webAccessMonth || $webAccessYear) {
			$ret .= $this->formatAccessDate($webAccessDay, $webAccessMonth, $webAccessYear) . '. ';
		}
		//Add the URL (if provided)
		if ($webUrl) {
			$ret .= '&#60;';
			$ret .= Utility::checkUrlPrepend($webUrl);
			$ret .= '&#62;';
			$ret .= '. ';
		}
		echo $ret;
	}
}
