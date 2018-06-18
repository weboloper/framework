<?php
/**
 * Phanbook : Delightfully simple forum software
 *
 * Licensed under The BSD License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @link    http://phanbook.com Phanbook Project
 * @since   1.0.0
 * @license https://github.com/phanbook/phanbook/blob/master/LICENSE.txt
 */
namespace Components\Clarity\View\Volt\Functions;

use function Stringy\create as s;

 
class Teaser
{
    /**
     * Truncates the text to a given length.
     *
     * @param string $text
     * @param int    $maxLen
     * @param bool   $saveWords
     * @param string $endWith
     *
     * @return string
     */
    public static function create($text, $maxLen = 35, $saveWords = true, $endWith = ' &hellip;')
    {

        $string = s($text);
        $length = $string->length();

        if ($length <= $maxLen) {
            return $text;
        }


        $string->trimRight('. ');

        if ($saveWords) {
            while ($maxLen < $length && preg_match('/^\pL$/', $string->substr($maxLen, 1))) {
                $maxLen++;
            }
        }
        return $string->substr(0, $maxLen) . $endWith;
    }
}