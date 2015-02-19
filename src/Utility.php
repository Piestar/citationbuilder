<?php namespace Piestar\CitationBuilder;

class Utility {

	/**
	 * Uppercase the first word of a subtitle
	 *
	 * @param string $title
	 *
	 * @return string
	 */
	public static function uppercaseSubtitle($title)
	{
		if (preg_match('/:[ ]+[a-z]/', $title, $matches)) {
			$upperAfterColon = strtoupper($matches[0]);
			$title = preg_replace('/:[ ]+[a-z]/', $upperAfterColon, $title);
		}

		return $title;
	}

	/**
	 * Remove articles (A, An, The) before a string
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public static function removeArticle($string)
	{
		$patterns      = ['/A /', '/An /', '/The /'];
		$replacements  = [''];

		return preg_replace($patterns, $replacements, $string);
	}

	/**
	 * Force articles, prepositions, and conjuctions to lowercase
	 * @param string $str
	 *
	 * @return string
	 */
	public static function lowerArticles($str)
	{
		$str = str_replace(" A ", " a ", $str);
		$str = str_replace(" An ", " an ", $str);
		$str = str_replace(" And ", " and ", $str);
		$str = str_replace(" About ", " about ", $str);
		$str = str_replace(" As ", " as ", $str);
		$str = str_replace(" At ", " at ", $str);
		$str = str_replace(" Away ", " away ", $str);
		$str = str_replace(" But ", " but ", $str);
		$str = str_replace(" By ", " by ", $str);
		$str = str_replace(" Due ", " due ", $str);
		$str = str_replace(" For ", " for ", $str);
		$str = str_replace(" From ", " from ", $str);
		$str = str_replace(" In ", " in ", $str);
		$str = str_replace(" Into ", " into ", $str);
		$str = str_replace(" Like ", " like ", $str);
		$str = str_replace(" Of ", " of ", $str);
		$str = str_replace(" Off ", " off ", $str);
		$str = str_replace(" On ", " on ", $str);
		$str = str_replace(" Onto ", " onto ", $str);
		$str = str_replace(" Or ", " or ", $str);
		$str = str_replace(" Over ", " over ", $str);
		$str = str_replace(" Per ", " per ", $str);
		$str = str_replace(" Than ", " than ", $str);
		$str = str_replace(" The ", " the ", $str);
		$str = str_replace(" Till ", " till ", $str);
		$str = str_replace(" To ", " to ", $str);
		$str = str_replace(" Until ", " until ", $str);
		$str = str_replace(" Up ", " up ", $str);
		$str = str_replace(" Upon ", " upon ", $str);
		$str = str_replace(" Via ", " via ", $str);
		$str = str_replace(" With ", " with ", $str);
		$str = str_replace(" Within ", " within ", $str);
		$str = str_replace(" Without ", " without ", $str);
		$str = str_replace(" Within ", " within ", $str);
		$str = str_replace(" Within ", " within ", $str);

		return $str;
	}

	/**
	 * Add a period to the end of an article title unless it is a ".", "?", or "!"
	 *
	 * @param $articleTitle
	 *
	 * @return string
	 */
	public static function addPeriod($articleTitle)
	{
		$len   = strlen($articleTitle);
		$lastChar = substr($articleTitle, $len - 1, 1);
		if (($lastChar != ".") && ($lastChar != "?") && ($lastChar != "!")) {
			$articleTitle = $articleTitle . ".";
		}

		return $articleTitle;
	}

	/**
	 * Check if a day should be displayed based on a month selection
	 *
	 * @param $month
	 *
	 * @return bool
	 */
	public static function showDay($month)
	{
		return in_array($month, [
			"January", "February", "March", "April", "May", "June",
			"July", "August", "September", "October", "November", "December",
		]);
	}

	/** Check that a URL begins with "http://", "ftp://", "telnet://", or "gopher://" (case-insensitive).  If not, assume http and prepend "http://". */
	public static function checkUrlPrepend($webUrl)
	{
		$httpprefix   = strpos('http://', $webUrl);
		$httpsprefix  = strpos('https://', $webUrl);
		$ftpprefix    = strpos('ftp://', $webUrl);
		$telnetprefix = strpos('telnet://', $webUrl);
		$gopherprefix = strpos('gopher://', $webUrl);
		if (($httpprefix !== false) && ($ftpprefix !== false) && ($telnetprefix !== false) && ($gopherprefix !== false) && ($httpsprefix !== false)) {
			$webUrl = "http://" . $webUrl;
		}

		return $webUrl;
	}

	/** Format a name and pull the first initial */
	public static function firstInitial($name)
	{
		$initial = substr($name, 0, 1);
		$initial = strtoupper($initial);

		return $initial;
	}

}
