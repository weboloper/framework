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
namespace Components\Clarity\Providers;

use Components\Clarity\Support\Phalcon\Mvc\URL as BaseURL;

/**
 * This provider instantiates the @see \Components\Clarity\Support\Phalcon\Mvc\URL.
 */
class URL extends ServiceProvider
{
    /**
     * {@inheridoc}.
     */
    protected $alias = 'url';

    /**
     * {@inheridoc}.
     */
    protected $shared = true;

    /**
     * {@inheridoc}.
     */
    protected $after_module = true;

    /**
     * {@inheridoc}.
     */
    public function register()
    {
        return BaseURL::getInstance();
    }
}
