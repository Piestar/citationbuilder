<?php namespace Piestar\CitationBuilder\Styles;

use Piestar\CitationBuilder\Types\Work;
use Piestar\CitationBuilder\Utility;

/**
 * Modern Language Asoociation (MLA) format
 */
class Mla7 implements CitationStyle {

	/**
	 * Format a date published (MLA)
	 *
	 * @param $day
	 * @param $month
	 * @param $year
	 *
	 * @return string
	 */
	function formatPublishDate($day, $month, $year)
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
	 * @param array $authors
	 *
	 * @return string
	 */
	function formatAuthors($authors)
	{
		$count = count($authors);
		//Count the number of authors in the array
		$numAuthors = 0;
		//Count the number of editors in the array
		$numEditors = 0;
		foreach ($authors as $contributor) {
			if ($contributor['cselect'] == 'author') {
				$numAuthors ++;
			} elseif ($contributor['cselect'] == 'editor') {
				$numEditors ++;
			}
		}
		$ret = '';
		for ($i = 0; $i < $count; $i ++) {
			if ($authors[ $i ]['cselect'] == 'author') {
				//If this contributor is an author
				if ($i == 0) {
					//First time through the loop
					if ($numAuthors > 1) {
						//There is more than one author
						$ret .= ucwords($authors[ $i ]['lname']);
						if (($authors[ $i ]['fname'] || $authors[ $i ]['mi'])) {
							//The author is a person and not a corporation
							$ret .= ', ' . ucwords($authors[ $i ]['fname']);
							if ($authors[ $i ]['mi']) {
								$ret .= ' ' . ucwords($authors[ $i ]['mi']) . '.';
							}
						}
						$ret .= ',';
					} else {
						//There is only one author
						if (($authors[ $i ]['lname'] != 'Anonymous') || ( ! $authors[ $i ]['lname'] && ! $authors[ $i ]['fname'] && ! $authors[ $i ]['mi'])) {
							//The author is not Anonymous or blank
							$ret .= ucwords($authors[ $i ]['lname']);
							if (($authors[ $i ]['fname'] || $authors[ $i ]['mi'])) {
								//The author is a person and not a corporation
								$ret .= ', ' . ucwords($authors[ $i ]['fname']);
								if ($authors[ $i ]['mi']) {
									$ret .= ' ' . ucwords($authors[ $i ]['mi']);
								}
							}
							$ret .= '. ';
						}
					}
				} elseif (($i + 1) == $count) {
					//Last time through the loop
					if ($numAuthors > 1) {
						//There is more than one author
						$ret .= ' and ' . ucwords($authors[ $i ]['fname']) . ' ';
						if ($authors[ $i ]['mi']) {
							$ret .= ucwords($authors[ $i ]['mi']) . '. ';
						}
						$ret .= ucwords($authors[ $i ]['lname']) . '. ';
					} else {
						//There is only one author
						if (($authors[ $i ]['lname'] != 'Anonymous') || ( ! $authors[ $i ]['lname'] && ! $authors[ $i ]['fname'] && ! $authors[ $i ]['mi'])) {
							//The author is not Anonymous or blank
							$ret .= ucwords($authors[ $i ]['lname']) . ', ';
							$ret .= ucwords($authors[ $i ]['fname']);
							if ($authors[ $i ]['mi']) {
								$ret .= ' ' . ucwords($authors[ $i ]['mi']);
							}
							$ret .= '. ';
						}
					}
				} else {
					$ret .= ' ' . ucwords($authors[ $i ]['fname']) . ' ';
					if ($authors[ $i ]['mi']) {
						$ret .= ucwords($authors[ $i ]['mi']) . '. ';
					}
					$ret .= ucwords($authors[ $i ]['lname']) . ',';
				}
			} elseif (($authors[ $i ]['cselect'] == 'editor' && $numAuthors == 0)) {
				//If this contributor is an editor and there are no authors listed
				if ($i == 0) {
					//First time through the loop
					if ($numEditors > 1) {
						//There is more than one editor
						$ret .= ucwords($authors[ $i ]['lname']);
						if (($authors[ $i ]['fname'] || $authors[ $i ]['mi'])) {
							//The editor is a person and not a corporation
							$ret .= ', ' . ucwords($authors[ $i ]['fname']);
							if ($authors[ $i ]['mi']) {
								$ret .= ' ' . ucwords($authors[ $i ]['mi']) . '.';
							}
						}
						if ($numEditors > 2) {
							$ret .= ',';
						}
					} else {
						//There is only one editor
						if (($authors[ $i ]['lname'] != 'Anonymous') || ( ! $authors[ $i ]['lname'] && ! $authors[ $i ]['fname'] && ! $authors[ $i ]['mi'])) {
							//The editor is not Anonymous or blank
							$ret .= ucwords($authors[ $i ]['lname']);
							if (($authors[ $i ]['fname'] || $authors[ $i ]['mi'])) {
								//The editor is a person and not a corporation
								$ret .= ', ' . ucwords($authors[ $i ]['fname']);
								if ($authors[ $i ]['mi']) {
									$ret .= ' ' . ucwords($authors[ $i ]['mi']);
								}
							}
							$ret .= ', ed. ';
						}
					}
				} elseif (($i + 1) == $count) {
					//Last time through the loop
					if ($numEditors > 1) {
						//There is more than one editor
						$ret .= ' and ' . ucwords($authors[ $i ]['fname']) . ' ';
						if ($authors[ $i ]['mi']) {
							$ret .= ucwords($authors[ $i ]['mi']) . '. ';
						}
						$ret .= ucwords($authors[ $i ]['lname']) . ', eds. ';
					} else {
						//There is only one editor
						if (($authors[ $i ]['lname'] != 'Anonymous') || ( ! $authors[ $i ]['lname'] && ! $authors[ $i ]['fname'] && ! $authors[ $i ]['mi'])) {
							//The editor is not Anonymous or blank
							$ret .= ucwords($authors[ $i ]['lname']) . ', ';
							$ret .= ucwords($authors[ $i ]['fname']);
							if ($authors[ $i ]['mi']) {
								$ret .= ' ' . ucwords($authors[ $i ]['mi']);
							}
							$ret .= ', ed. ';
						}
					}
				} else {
					$ret .= ' ' . ucwords($authors[ $i ]['fname']) . ' ';
					if ($authors[ $i ]['mi']) {
						$ret .= ucwords($authors[ $i ]['mi']) . '. ';
					}
					$ret .= ucwords($authors[ $i ]['lname']) . ',';
				}
			}
		}

		return $ret;
	}

	/** Format the translator names (MLA)
	 *
	 * @param array $translators
	 *
	 * @return string
	 */
	function formatTranslators($translators)
	{
		$count = count($translators);
		//Count the number of authors in the array
		$numAuthors = 0;
		//Count the number of translators in the array
		$numTranslators = 0;
		foreach ($translators as $contributor) {
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
			if ($translators[ $i ]['cselect'] == 'translator') {
				//If this contributor is an translator
				if ($t == 0) {
					//First time through the loop
					if ($numTranslators > 1) {
						//There is more than one translator
						$ret .= 'Trans. ';
						$ret .= ucwords($translators[ $i ]['fname']) . ' ';
						if ($translators[ $i ]['mi']) {
							$ret .= ucwords($translators[ $i ]['mi']) . ' ';
						}
						$ret .= ucwords($translators[ $i ]['lname']);
						//If there are more than two translators, add a comma after the name
						if ($numTranslators > 2) {
							$ret .= ',';
						}
					} else {
						//There is only one translator
						if (($translators[ $i ]['lname'] != 'Anonymous') || ( ! $translators[ $i ]['lname'] && ! $translators[ $i ]['fname'] && ! $translators[ $i ]['mi'])) {
							//The translator is not Anonymous or blank
							$ret .= 'Trans. ';
							$ret .= ucwords($translators[ $i ]['fname']) . ' ';
							if ($translators[ $i ]['mi']) {
								$ret .= ucwords($translators[ $i ]['mi']) . ' ';
							}
							$ret .= ucwords($translators[ $i ]['lname']) . '. ';
						}
					}
				} elseif (($t + 1) == $numTranslators) {
					//Last time through the loop
					if ($numTranslators > 1) {
						//There is more than one translator
						$ret .= ' and ' . ucwords($translators[ $i ]['fname']) . ' ';
						if ($translators[ $i ]['mi']) {
							$ret .= ucwords($translators[ $i ]['mi']) . ' ';
						}
						$ret .= ucwords($translators[ $i ]['lname']) . '. ';
					} else {
						//There is only one translator
						if (($translators[ $i ]['lname'] != 'Anonymous') || ( ! $translators[ $i ]['lname'] && ! $translators[ $i ]['fname'] && ! $translators[ $i ]['mi'])) {
							//The translator is not Anonymous or blank
							$ret .= 'Trans. ';
							$ret .= ucwords($translators[ $i ]['fname']) . ' ';
							if ($translators[ $i ]['mi']) {
								$ret .= ucwords($translators[ $i ]['mi']) . ' ';
							}
							$ret .= ucwords($translators[ $i ]['lname']) . '. ';
						}
					}
				} else {
					$ret .= ' ' . ucwords($translators[ $i ]['fname']) . ' ';
					if ($translators[ $i ]['mi']) {
						$ret .= ucwords($translators[ $i ]['mi']) . ' ';
					}
					$ret .= ucwords($translators[ $i ]['lname']) . ',';
				}
				$t ++;
			}
		}

		return $ret;
	}

	/**
	 * Format the editor names, if there is an author (MLA)
	 *
	 * @param $editors
	 *
	 * @return string
	 */
	function formatEditors($editors)
	{
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
			if (($editors[ $i ]['cselect'] == 'editor') && ($numAuthors != 0)) {
				//If this contributor is an editor and there are no authors
				if ($t == 0) {
					//First time through the loop
					if ($numEditors > 1) {
						//There is more than one editor
						$ret .= 'Ed. ';
						$ret .= ucwords($editors[ $i ]['fname']);
						if ($editors[ $i ]['mi']) {
							$ret .= ' ' . ucwords($editors[ $i ]['mi']) . ' ';
						}
						$ret .= ucwords($editors[ $i ]['lname']);
						//If there are more than two editors, add a comma after the name
						if ($numEditors > 2) {
							$ret .= ',';
						}
					} else {
						//There is only one editor
						if (($editors[ $i ]['lname'] != 'Anonymous') || ( ! $editors[ $i ]['lname'] && ! $editors[ $i ]['fname'] && ! $editors[ $i ]['mi'])) {
							//The editor is not Anonymous or blank
							$ret .= 'Ed. ';
							$ret .= ucwords($editors[ $i ]['fname']) . ' ';
							if ($editors[ $i ]['mi']) {
								$ret .= ' ' . ucwords($editors[ $i ]['mi']) . ' ';
							}
							$ret .= ucwords($editors[ $i ]['lname']) . '. ';
						}
					}
				} elseif (($t + 1) == $numEditors) {
					//Last time through the loop
					if ($numEditors > 1) {
						//There is more than one editor
						$ret .= ' and ' . ucwords($editors[ $i ]['fname']) . ' ';
						if ($editors[ $i ]['mi']) {
							$ret .= ucwords($editors[ $i ]['mi']) . ' ';
						}
						$ret .= ucwords($editors[ $i ]['lname']) . '. ';
					} else {
						//There is only one editor
						if (($editors[ $i ]['lname'] != 'Anonymous') || ( ! $editors[ $i ]['lname'] && ! $editors[ $i ]['fname'] && ! $editors[ $i ]['mi'])) {
							//The editor is not Anonymous or blank
							$ret .= 'Ed. ';
							$ret .= ucwords($editors[ $i ]['fname']);
							if ($editors[ $i ]['mi']) {
								$ret .= ' ' . ucwords($editors[ $i ]['mi']) . ' ';
							}
							$ret .= ucwords($editors[ $i ]['lname']) . '. ';
						}
					}
				} else {
					$ret .= ' ' . ucwords($editors[ $i ]['fname']) . ' ';
					if ($editors[ $i ]['mi']) {
						$ret .= ucwords($editors[ $i ]['mi']) . ' ';
					}
					$ret .= ucwords($editors[ $i ]['lname']) . ',';
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

	/**
	 * Creates a book citation
	 *
	 * @param Work $work
	 */
	function book(Work $work)
	{
		//Add the contributors
		$ret = $this->formatAuthors($work->contributors);
		//Add the book title (if provided)
		if ($work->title) {
			$ret .= $this->formatBookTitle($work->title) . ' ';
		}
		//Add the translators (if no authors)
		$ret .= $this->formatTranslators($work->contributors);
		//Add the editors (if no authors)
		$ret .= $this->formatEditors($work->contributors);
		//Add the publisher location (if provided)
		if ($work->publisherLocation) {
			$ret .= ucwords($work->publisherLocation) . ': ';
		}
		//Add the publisher (if provided)
		if ($work->publisher) {
			$ret .= ucwords($work->publisher) . ', ';
		}
		//Add the publication year (if provided)
		if ($work->publicationYear) {
			$ret .= $work->publicationYear . '. ';
		}
		//in print
		if ($work->medium == "print") {
			//Add the medium
			$ret .= 'Print.';
		}
		//on a website
		if ($work->medium == "website") {
			//Add the title of the website (if provided)
			if ($work->webTitle) {
				$ret .= '<i>' . ucwords($work->webTitle) . '</i>' . '. ';
			}
			//Add the medium
			$ret .= 'Web. ';
			//Add the access date (if provided)
			if ($work->webAccessDay || $work->webAccessMonth || $work->webAccessYear) {
				$ret .= $this->formatAccessDate($work->webAccessDay, $work->webAccessMonth, $work->webAccessYear) . '. ';
			}
			//Add the URL (if provided)
			if ($work->webUrl) {
				$ret .= '&#60;';
				$ret .= Utility::checkUrlPrepend($work->webUrl);
				$ret .= '&#62;';
				$ret .= '. ';
			}

		}
		//in a database
		if ($work->medium == "db") {
			//Add the database title (if provided)
			if ($work->db) {
				$ret .= '<i>' . ucwords($work->db) . '</i>' . '. ';
			}
			//Add the medium
			$ret .= 'Web. ';
			//Add the access date (if provided)
			if ($work->dbAccessDay || $work->dbAccessMonth || $work->dbAccessYear) {
				$ret .= $this->formatAccessDate($work->dbAccessDay, $work->dbAccessMonth, $work->dbAccessYear) . '. ';
			}
			//Add the URL (if provided)
			if ($work->dbUrl) {
				$ret .= '&#60;';
				$ret .= Utility::checkUrlPrepend($work->dbUrl);
				$ret .= '&#62;';
				$ret .= '. ';
			}
		}
		//as a digital file
		if ($work->medium == "ebook") {
			//Add the Medium
			$ret .= $this->formatEbookMedium($work->medium) . '. ';
			//Add the URL (if provided)
			if ($work->ebookUrl) {
				$ret .= '&#60;';
				$ret .= Utility::checkUrlPrepend($work->ebookUrl);
				$ret .= '&#62;';
				$ret .= '. ';
			}
		}
		return $ret;
	}

	/** Creates a chapter or essay from a book citation */
	function chapter(Work $work)
	{
		//Add the contributors
		$ret = $this->formatAuthors($work->contributors);
		//Add the translators (if no authors)
		$ret .= $this->formatTranslators($work->contributors);
		//Add the chapter/essay title (if provided)
		if ($work->chapterTitle) {
			//Uppercase all words in chapter/essay title, lowercase all articles, prepositions, & conjunctions, append a period, and encapsulate in double quotes
			$chapterTitle = ucwords($work->chapterTitle);
			$chapterTitle = Utility::lowerArticles($chapterTitle);
			$chapterTitle = Utility::addPeriod($chapterTitle);
			$ret .= '"' . $chapterTitle . '" ';
		}
		//Add the book title (if provided)
		if ($work->bookTitle) {
			$ret .= $this->formatBookTitle($work->bookTitle) . ' ';
		}
		//Add the translators (if no authors)
		$ret .= $this->formatTranslators($work->contributors);
		//Add the editors (if no authors)
		$ret .= $this->formatEditors($work->contributors);
		//Add the publisher location (if provided)
		if ($work->publisherLocation) {
			$ret .= ucwords($work->publisherLocation) . ': ';
		}
		//Add the publisher (if provided)
		if ($work->publisher) {
			$ret .= ucwords($work->publisher) . ', ';
		}
		//Add the publication year (if provided)
		if ($work->publicationYear) {
			$ret .= $work->publicationYear . '. ';
		}
		//Add the page numbers
		$ret .= $this->getPageNumbers($work->startPage, $work->endPage, $work->hasNonConsecutivePages);
		//in print
		if ($work->medium == "print") {
			//Add the medium
			$ret .= 'Print.';
		}
		//on a website
		if ($work->medium == "website") {
			//Add the title of the website (if provided)
			if ($work->webTitle) {
				$ret .= '<i>' . ucwords($work->webTitle) . '</i>' . '. ';
			}
			//Add the medium
			$ret .= 'Web. ';
			//Add the access date (if provided)
			if ($work->webAccessDay || $work->webAccessMonth || $work->webAccessYear) {
				$ret .= $this->formatAccessDate($work->webAccessDay, $work->webAccessMonth, $work->webAccessYear) . '. ';
			}
			//Add the URL (if provided)
			if ($work->webUrl) {
				$ret .= '&#60;';
				$ret .= Utility::checkUrlPrepend($work->webUrl);
				$ret .= '&#62;';
				$ret .= '. ';
			}

		}
		//in a database
		if ($work->medium == "db") {
			//Add the database title (if provided)
			if ($work->db) {
				$ret .= '<i>' . ucwords($work->db) . '</i>' . '. ';
			}
			//Add the medium
			$ret .= 'Web. ';
			//Add the access date (if provided)
			if ($work->dbAccessDay || $work->dbAccessMonth || $work->dbAccessYear) {
				$ret .= $this->formatAccessDate($work->dbAccessDay, $work->dbAccessMonth, $work->dbAccessYear) . '. ';
			}
			//Add the URL (if provided)
			if ($work->dbUrl) {
				$ret .= '&#60;';
				$ret .= Utility::checkUrlPrepend($work->dbUrl);
				$ret .= '&#62;';
				$ret .= '. ';
			}
		}
		return $ret;
	}

	/**
	 * Creates a magazine article citation
	 *
	 * @param Work $work
	 */
	function magazine(Work $work)
	{
		//Add the contributors
		$ret = $this->formatAuthors($work->contributors);
		//Add the article title (if provided)
		if ($work->articleTitle) {
			//Uppercase all words in article title, lowercase all art., prep., & conj., append a period, and encapsulate in double quotes
			$articleTitle = ucwords($work->articleTitle);
			$articleTitle = Utility::lowerArticles($articleTitle);
			$articleTitle = Utility::addPeriod($articleTitle);
			$ret .= '"' . $articleTitle . '" ';
		}
		//in print
		if ($work->medium == "print") {
			//Add the magazine title (if provided)
			if ($work->magazineTitle) {
				$magtitleholder = ucwords($work->magazineTitle);
				$ret .= '<i>' . Utility::lowerArticles($magtitleholder) . '</i>' . ' ';
			}
			//Add the date published (if provided)
			if ($work->publishedDay || $work->publishedMonth || $work->publishedYear) {
				$ret .= $this->formatPublishDate($work->publishedDay, $work->publishedMonth, $work->publishedYear);
				//Add a colon
				$ret .= ': ';
			}
			//Add the page numbers
			$ret .= $this->getPageNumbers($work->startPage, $work->endPage, $work->hasNonConsecutivePages);
			//Add the medium
			$ret .= 'Print.';
		}
		//on website
		if ($work->medium == "website") {
			//Add the website publisher/sponsor (if provided)
			if ($work->magazineTitle) {
				$ret .= '<i>' . ucwords($work->magazineTitle) . '</i>' . '. ';
			} else {
				$ret .= 'N.p., ';
			}
			//Add the website title (if provided)
			if ($work->webTitle) {
				$ret .= ucwords($work->webTitle) . ', ';
			}
			//Add the date published (if provided)
			$ret .= $this->formatPublishDate($work->publishedDay, $work->publishedMonth, $work->publishedYear);
			//Add a period
			$ret .= '. ';
			//Add the medium
			$ret .= 'Web. ';
			//Add the access date (if provided)
			if ($work->webAccessDay || $work->webAccessMonth || $work->webAccessYear) {
				$ret .= $this->formatAccessDate($work->webAccessDay, $work->webAccessMonth, $work->webAccessYear) . '. ';
			}
			//Add the URL (if provided)
			if ($work->webUrl) {
				$ret .= '&#60;';
				$ret .= Utility::checkUrlPrepend($work->webUrl);
				$ret .= '&#62;';
				$ret .= '. ';
			}
		}
		//in a database
		if ($work->medium == "db") {
			//Add the magazine title (if provided)
			if ($work->magazineTitle) {
				$magtitleholder = ucwords($work->magazineTitle);
				$ret .= '<i>' . Utility::lowerArticles($magtitleholder) . '</i>' . ' ';
			}
			//Add the date published (if provided)
			$ret .= $this->formatPublishDate($work->publishedDay, $work->publishedMonth, $work->publishedYear);
			//Add a period
			$ret .= '. ';
			//Add the page numbers
			$ret .= $this->getPageNumbers($work->dbStartPage, $work->dbEndPage, $work->dbHasNonConsecutive);
			//Add the database title (if provided)
			if ($work->db) {
				$ret .= '<i>' . ucwords($work->db) . '</i>' . '. ';
			}
			//Add the medium
			$ret .= 'Web. ';
			//Add the access date (if provided)
			if ($work->dbAccessDay || $work->dbAccessMonth || $work->dbAccessYear) {
				$ret .= $this->formatAccessDate($work->dbAccessDay, $work->dbAccessMonth, $work->dbAccessYear) . '. ';
			}
			//Add the URL (if provided)
			if ($work->dbUrl) {
				$ret .= '&#60;';
				$ret .= Utility::checkUrlPrepend($work->dbUrl);
				$ret .= '&#62;';
				$ret .= '. ';
			}
		}
		return $ret;
	}

	/**
	 * Creates a newspaper article citation
	 *
	 * @param Work $work
	 */
	function newspaper(Work $work)
	{
		//Add the contributors
		$ret = $this->formatAuthors($work->contributors);
		//Add the article title (if provided)
		if ($work->articleTitle) {
			//Uppercase all words in article title, lowercase all art., prep., & conj., append a period, and encapsulate in double quotes
			$articleTitle = ucwords($work->articleTitle);
			$articleTitle = Utility::lowerArticles($articleTitle);
			$articleTitle = Utility::addPeriod($articleTitle);
			$ret .= '"' . $articleTitle . '" ';
		}
		//in print
		if ($work->medium == "print") {
			//Add the newspaper title (if provided)
			if ($work->newspaperTitle) {
				//Uppercase all words in a newspaper's title
				$newspaperTitle = ucwords($work->newspaperTitle);
				//Remove articles (A, An, The) before the newspaper title 
				$newspaperTitle = Utility::removeArticle($newspaperTitle);
				$ret .= '<i>' . $newspaperTitle . '</i>' . ' ';
			}
			//Add the newspaper city (if provided)
			if ($work->newspaperCity) {
				$ret .= '[' . ucwords($work->newspaperCity) . ']' . ' ';
			}
			//Add the date published (if provided)
			if ($work->day || $work->month || $work->year) {
				$ret .= $this->formatPublishDate($work->day, $work->month, $work->year);
			}
			//Add the edition (if provided)
			if ($work->edition) {
				$edition = strtolower($work->edition);
				$ret .= ', ' . $this->abbreviateEdition($edition);
			}
			//Add the section (if provided)
			if ($work->section) {
				$ret .= ', ' . $this->formatNewspaperSection($work->section);
			}
			//Add a colon
			$ret .= ': ';
			//Add the page numbers
			$ret .= $this->getPageNumbers($work->startPage, $work->endPage, $work->hasNonConsecutivePages);
			//Add the medium
			$ret .= 'Print.';
		}
		//on a website
		if ($work->medium == "website") {
			//Add the web site title (if provided)
			if ($work->webTitle) {
				$ret .= '<i>' . ucwords($work->webTitle) . '</i>' . '. ';
			}
			//Add the newspaper title (if provided)
			if ($work->newspaperTitle) {
				//Uppercase all words in a newspaper's title
				$newspaperTitle = ucwords($work->newspaperTitle);
				//Remove articles (A, An, The) before the newspaper title 
				$newspaperTitle = Utility::removeArticle($newspaperTitle);
				$ret .= '<i>' . $newspaperTitle . '</i>' . ', ';
			}
			//Add the electronically published date (if provided)
			if ($work->electronicPublishDay || $work->electronicPublishMonth || $work->electronicPublishYear) {
				$ret .= $this->formatPublishDate($work->electronicPublishDay, $work->electronicPublishMonth, $work->electronicPublishYear) . '. ';
			}
			//Add the medium
			$ret .= 'Web. ';
			//Add the access date (if provided) 
			if ($work->webAccessDay || $work->webAccessMonth || $work->webAccessYear) {
				$ret .= $this->formatAccessDate($work->webAccessDay, $work->webAccessMonth, $work->webAccessYear) . '. ';
			}
			//Add the URL (if provided)
			if ($work->webUrl) {
				$ret .= '&#60;';
				$ret .= Utility::checkUrlPrepend($work->webUrl);
				$ret .= '&#62;';
				$ret .= '. ';
			}
		}
		//in a database
		if ($work->medium == "db") {
			//Add the newspaper title (if provided)
			if ($work->newspaperTitle) {
				//Uppercase all words in a newspaper's title
				$newspaperTitle = ucwords($work->newspaperTitle);
				//Remove articles (A, An, The) before the newspaper title 
				$newspaperTitle = Utility::removeArticle($newspaperTitle);
				$ret .= '<i>' . $newspaperTitle . '</i>' . ' ';
			}
			//Add the newspaper city (if provided)
			if ($work->dbNewspaperCity) {
				$ret .= '[' . ucwords($work->dbNewspaperCity) . ']' . ' ';
			}
			//Add the date published (if provided)
			if ($work->dbPublishedDay || $work->dbPublishedMonth || $work->dbPublishedYear) {
				$ret .= $this->formatPublishDate($work->dbPublishedDay, $work->dbPublishedMonth, $work->dbPublishedYear);
			}
			//Add the edition (if provided)
			if ($work->dbEdition) {
				$dbEdition = strtolower($work->dbEdition);
				$ret .= ', ' . $this->abbreviateEdition($dbEdition);
			}
			//Add a colon
			$ret .= ': ';
			//Add the page numbers
			$ret .= $this->getPageNumbers($work->dbStartPage, $work->dbEndPage, $work->dbHasNonConsecutive);
			//Add the database title (if provided)
			if ($work->db) {
				$ret .= '<i>' . ucwords($work->db) . '</i>' . '. ';
			}
			//Add the medium
			$ret .= 'Web. ';
			//Add the access date
			$ret .= $this->formatAccessDate($work->dbAccessDay, $work->dbAccessMonth, $work->dbAccessYear) . '. ';
			//Add the URL (if provided)
			if ($work->dbUrl) {
				$ret .= '&#60;';
				$ret .= Utility::checkUrlPrepend($work->dbUrl);
				$ret .= '&#62;';
				$ret .= '. ';
			}
		}
		return $ret;
	}

	/**
	 * Creates a scholarly journal article citation
	 *
	 * @param Work $work
	 */
	function journal(Work $work)
	{
		//Add the contributors
		$ret = $this->formatAuthors($work->contributors);
		//Add the article title (if provided)
		if ($work->articleTitle) {
			//Uppercase all words in article title, lowercase all art., prep., & conj., append a period, and encapsulate in double quotes
			$articleTitle = ucwords($work->articleTitle);
			$articleTitle = Utility::lowerArticles($articleTitle);
			$articleTitle = Utility::addPeriod($articleTitle);
			$ret .= '"' . $articleTitle . '" ';
		}
		//Add the journal title (if provided)
		if ($work->journalTitle) {
			$journalTitleholder = ucwords($work->journalTitle);
			$ret .= '<i>' . Utility::lowerArticles($journalTitleholder) . ' </i>';
		}
		//Add the volume number (if provided)
		if ($work->volume) {
			$ret .= $work->volume;
		}
		//Add the issue number (if provided)
		if ($work->issue) {
			$ret .= '.' . $work->issue . ' ';
		}
		//Add the date published (if provided)
		if ($work->yearPublished) {
			$ret .= $this->formatYearPublished($work->yearPublished);
		}
		//Add the page numbers
		$ret .= $this->getPageNumbers($work->startPage, $work->endPage, $work->hasNonConsecutivePages);
		//in print
		if ($work->medium == "print") {
			//Add the medium
			$ret .= 'Print.';
		}
		//on a website
		if ($work->medium == "website") {
			//Add the medium
			$ret .= 'Web. ';
			//Add the access date (if provided)
			if ($work->webAccessDay || $work->webAccessMonth || $work->webAccessYear) {
				$ret .= $this->formatAccessDate($work->webAccessDay, $work->webAccessMonth, $work->webAccessYear) . '. ';
			}
			//Add the URL (if provided)
			if ($work->webUrl) {
				$ret .= '&#60;';
				$ret .= Utility::checkUrlPrepend($work->webUrl);
				$ret .= '&#62;';
				$ret .= '. ';
			}
		}
		//in a database
		if ($work->medium == "db") {
			//Add the database title (if provided)
			if ($work->db) {
				$ret .= '<i>' . ucwords($work->db) . '</i>' . '. ';
			}
			//Add the medium
			$ret .= 'Web. ';
			//Add the access date (if provided)
			if ($work->dbAccessDay || $work->dbAccessMonth || $work->dbAccessYear) {
				$ret .= $this->formatAccessDate($work->dbAccessDay, $work->dbAccessMonth, $work->dbAccessYear) . '. ';
			}
			//Add the URL (if provided)
			if ($work->dbUrl) {
				$ret .= '&#60;';
				$ret .= Utility::checkUrlPrepend($work->dbUrl);
				$ret .= '&#62;';
				$ret .= '. ';
			}
		}
		return $ret;
	}

	/** Creates a web site citation */
	function website()
	{
		//Add the contributors
		$ret = $this->formatAuthors($work->contributors);
		//Add the article title (if provided)
		if ($work->articleTitle) {
			//Uppercase all words in article title, lowercase all art., prep., & conj., append a period, and encapsulate in double quotes
			$articleTitle = ucwords($work->articleTitle);
			$articleTitle = Utility::lowerArticles($articleTitle);
			$articleTitle = Utility::addPeriod($articleTitle);
			$ret .= '"' . $articleTitle . '" ';
		}
		//Add the web site title (if provided)
		if ($work->webTitle) {
			$ret .= '<i>' . ucwords($work->webTitle) . '</i>' . '. ';
		}
		//Add the web site publisher/sponsor (if provided)
		if ($work->publisherSponsor) {
			$ret .= ucwords($work->publisherSponsor) . ', ';
		} else {
			$ret .= 'N.p., ';
		}
		//Add the electronically published date (if provided)
		$ret .= $this->formatPublishDate($work->electronicPublishDay, $work->electronicPublishMonth, $work->electronicPublishYear);
		//Add a period
		$ret .= '. ';
		//Add the medium
		$ret .= 'Web. ';
		//Add the access date (if provided)
		if ($work->webAccessDay || $work->webAccessMonth || $work->webAccessYear) {
			$ret .= $this->formatAccessDate($work->webAccessDay, $work->webAccessMonth, $work->webAccessYear) . '. ';
		}
		//Add the URL (if provided)
		if ($work->webUrl) {
			$ret .= '&#60;';
			$ret .= Utility::checkUrlPrepend($work->webUrl);
			$ret .= '&#62;';
			$ret .= '. ';
		}
		return $ret;
	}

	function __toString() {
		return 'MLA7';
	}
}
