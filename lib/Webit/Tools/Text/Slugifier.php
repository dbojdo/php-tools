<?php
namespace Webit\Tools\Text;

/**
 * Webit\Tools\Text\Slugifier
 * @author dbojdo
 */
class Slugifier {
	/**
	 * 
	 * @param string $text
	 * @return string
	 */
	static public function slugify($text) {
		// remove double or more whitespaces
		// replace non letter or digits by -
		$text = preg_replace(array('/\s{2,}/','~[^\\pL\d]+~u'),array('','-'),$text);

		// trim
		$text = trim($text, '-');

		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		// lowercase
		$text = mb_strtolower($text);

		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		return $text;
	}
}
?>
