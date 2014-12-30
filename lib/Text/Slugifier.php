<?php
namespace Webit\Tools\Text;

use Gedmo\Sluggable\Util\Urlizer;

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
    public static function slugify($text, $separtor = '-')
    {
    	$slug = Urlizer::transliterate($text, $separtor);
    	$slug = Urlizer::urlize($slug, $separtor);
    	$slug = mb_strtolower($slug);
    	
    	return $slug;
    }
}
