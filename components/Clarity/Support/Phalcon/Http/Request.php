<?php
/**
 * PhalconSlayer\Framework.
 *
 * @copyright 2015-2016 Daison Carino <daison12006013@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://docs.phalconslayer.com
 */

/**
 */
namespace Components\Clarity\Support\Phalcon\Http;

use Components\Clarity\Support\Curl\RESTful;
use Phalcon\Http\Request as BaseRequest;

class Request extends BaseRequest
{
    public function module($name)
    {
        return new RESTful(url()->getFullUrl($name));
    }
}
