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

use Components\Model\Posts;
 
class Posts
{
    
    public function get_the_post_thumbnail( Posts $object , string|array $size = 'post-thumbnail', string|array $attr = '' )
    {

        return '<img src="https://i.imgur.com/sRzD0H4.jpg" />';
    }
}