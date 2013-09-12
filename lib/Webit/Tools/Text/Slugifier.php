<?php
namespace Webit\Tools\Text;

/**
 * Webit\Tools\Text\Slugifier
 * @author dbojdo
 */
class Slugifier
{
    /**
     * @param  string $text
     * @return string
     */
    public static function slugify($text)
    {
        // remove double or more whitespaces
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // lowercase
        $text = mb_strtolower($text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('/\s{2,}/','',$text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        $text = empty($text) ? null : $text;

        return $text;
    }
}
