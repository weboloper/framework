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

use InvalidArgumentException;

class Middleware
{
    protected $middlewares = [];

    public function __construct($middlewares = [])
    {
        $this->middlewares = $middlewares;
    }

    public function get($alias)
    {
        if (! isset($this->middlewares[$alias])) {
            throw new InvalidArgumentException(
                "Middleware based on alias [$alias] not found."
            );
        }

        return $this->middlewares[$alias];
    }

    public function set($middlewares)
    {
        $this->middlewares = $middlewares;

        return $this;
    }
}
